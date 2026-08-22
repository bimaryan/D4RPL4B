<?php

namespace App\Http\Controllers;

use App\Models\Hosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileManagerController extends Controller
{
    private function resolveHosting(?Hosting $hosting)
    {
        if ($hosting && $hosting->exists) {
            return $hosting;
        }
        
        $student = \Illuminate\Support\Facades\Auth::guard('student')->user();
        if ($student && $student->hosting) {
            return $student->hosting;
        }

        abort(404);
    }

    public function index(Request $request, ?Hosting $hosting = null)
    {
        $hosting = $this->resolveHosting($hosting);
        $rel = $this->cleanPath($request->query('path', ''));
        $full = $this->fullPath($hosting, $rel);

        if (!is_dir($full)) {
            $rel = '';
            $full = $this->fullPath($hosting, '');
        }

        $items = [];
        if (is_dir($full)) {
            foreach (scandir($full) as $name) {
                if ($name === '.' || $name === '..') continue;
                $p = $full . '/' . $name;
                $isDir = is_dir($p);
                $relPath = ltrim($rel . '/' . $name, '/');
                $items[] = [
                    'name' => $name,
                    'path' => $relPath,
                    'is_dir' => $isDir,
                    'size' => $isDir ? '-' : $this->formatBytes(filesize($p)),
                    'bytes' => $isDir ? 0 : filesize($p),
                    'mtime' => date('Y-m-d H:i', filemtime($p)),
                    'ext' => $isDir ? 'folder' : strtolower(pathinfo($name, PATHINFO_EXTENSION)),
                ];
            }
            // dirs first
            usort($items, fn($a,$b)=> $b['is_dir'] <=> $a['is_dir'] ?: strcmp($a['name'],$b['name']));
        }

        $breadcrumbs = $this->breadcrumbs($rel);
        $usage = $hosting->diskUsage();

        if ($request->expectsJson()) {
            return response()->json(['items'=>$items, 'path'=>$rel, 'breadcrumbs'=>$breadcrumbs, 'usage'=>$usage]);
        }

        $isAdmin = Auth::guard('web')->check();
        if ($isAdmin) {
            $hashId = $hosting->hash_id;
            $r_upload     = route('hostings.files.upload',     $hashId);
            $r_mkdir      = route('hostings.files.mkdir',      $hashId);
            $r_mkfile     = route('hostings.files.mkfile',     $hashId);
            $r_rename     = route('hostings.files.rename',     $hashId);
            $r_update     = route('hostings.files.update',     $hashId);
            $r_delete     = route('hostings.files.destroy',    $hashId);
            $r_edit       = route('hostings.files.edit',       $hashId);
            $r_download   = route('hostings.files.download',   $hashId);
            $r_extract    = route('hostings.files.extract',    $hashId);
            $r_bulk_delete = route('hostings.files.bulk-delete', $hashId);
            $r_paste      = route('hostings.files.paste',      $hashId);
            $r_back       = route('hostings.show', $hosting);
        } else {
            $r_upload     = route('mahasiswa.hosting.files.upload');
            $r_mkdir      = route('mahasiswa.hosting.files.mkdir');
            $r_mkfile     = route('mahasiswa.hosting.files.mkfile');
            $r_rename     = route('mahasiswa.hosting.files.rename');
            $r_update     = route('mahasiswa.hosting.files.update');
            $r_delete     = route('mahasiswa.hosting.files.destroy');
            $r_edit       = route('mahasiswa.hosting.files.edit');
            $r_download   = route('mahasiswa.hosting.files.download');
            $r_extract    = route('mahasiswa.hosting.files.extract');
            $r_bulk_delete = route('mahasiswa.hosting.files.bulk-delete');
            $r_paste      = route('mahasiswa.hosting.files.paste');
            $r_back       = route('mahasiswa.dashboard');
        }

        return view('admin.hostings.filemanager', compact(
            'hosting', 'items', 'rel', 'breadcrumbs', 'usage', 'isAdmin',
            'r_upload', 'r_mkdir', 'r_mkfile', 'r_rename', 'r_update',
            'r_delete', 'r_edit', 'r_download', 'r_extract', 'r_bulk_delete',
            'r_paste', 'r_back'
        ));
    }

    public function upload(Request $request, ?Hosting $hosting = null)
    {
        $hosting = $this->resolveHosting($hosting);
        $request->validate([
            'files.*' => 'required|file|max:51200',
            'path' => 'nullable|string',
        ]);
        $rel = $this->cleanPath($request->input('path',''));
        $full = $this->fullPath($hosting, $rel);
        if (!is_dir($full)) mkdir($full, 0755, true);

        foreach ($request->file('files', []) as $file) {
            $name = $this->sanitize($file->getClientOriginalName());
            // Convert images to webp? For hosting, keep original for portfolio, but we can keep as is for full hosting
            $file->move($full, $name);
        }

        return back()->with('success', 'Upload berhasil.');
    }

    public function mkdir(Request $request, ?Hosting $hosting = null)
    {
        $hosting = $this->resolveHosting($hosting);
        $request->validate(['name'=>'required|string|max:100', 'path'=>'nullable|string']);
        $rel = $this->cleanPath($request->input('path',''));
        $name = $this->sanitize($request->input('name'));
        $full = $this->fullPath($hosting, $rel) . '/' . $name;
        if (!is_dir($full)) mkdir($full, 0755, true);
        return back()->with('success', "Folder $name dibuat.");
    }

    public function mkfile(Request $request, ?Hosting $hosting = null)
    {
        $hosting = $this->resolveHosting($hosting);
        $request->validate(['name'=>'required|string|max:100', 'path'=>'nullable|string', 'content'=>'nullable|string']);
        $rel = $this->cleanPath($request->input('path',''));
        $name = $this->sanitize($request->input('name'));
        $full = $this->fullPath($hosting, $rel) . '/' . $name;
        file_put_contents($full, $request->input('content',''));
        return back()->with('success', "File $name dibuat.");
    }

    public function edit(Request $request, ?Hosting $hosting = null)
    {
        $hosting = $this->resolveHosting($hosting);
        $path = $this->cleanPath($request->query('path'));
        $full = $this->fullPath($hosting, $path);
        if (!is_file($full)) abort(404);
        $content = file_get_contents($full);
        // Prevent huge files
        if (strlen($content) > 500000) $content = substr($content,0,500000) . "\n\n... truncated ...";
        return response()->json(['content'=>$content, 'path'=>$path, 'name'=>basename($path)]);
    }

    public function update(Request $request, ?Hosting $hosting = null)
    {
        $hosting = $this->resolveHosting($hosting);
        $request->validate(['path'=>'required|string', 'content'=>'nullable|string']);
        $path = $this->cleanPath($request->input('path'));
        $full = $this->fullPath($hosting, $path);
        if (!is_file($full)) abort(404);
        file_put_contents($full, $request->input('content',''));
        return back()->with('success', 'File disimpan.');
    }

    public function rename(Request $request, ?Hosting $hosting = null)
    {
        $hosting = $this->resolveHosting($hosting);
        $request->validate(['path'=>'required|string', 'new_name'=>'required|string|max:100']);
        $old = $this->cleanPath($request->input('path'));
        $newName = $this->sanitize($request->input('new_name'));
        $dir = dirname($old);
        $new = $dir === '.' ? $newName : $dir . '/' . $newName;
        $oldFull = $this->fullPath($hosting, $old);
        $newFull = $this->fullPath($hosting, $new);
        if (file_exists($newFull)) return back()->withErrors(['new_name'=>'Nama sudah ada']);
        rename($oldFull, $newFull);
        return back()->with('success', 'Rename berhasil.');
    }

    public function destroy(Request $request, ?Hosting $hosting = null)
    {
        $hosting = $this->resolveHosting($hosting);
        $request->validate(['path'=>'required|string']);
        $path = $this->cleanPath($request->input('path'));
        $full = $this->fullPath($hosting, $path);
        if (!file_exists($full)) return back()->withErrors(['path'=>'File tidak ditemukan']);
        if (is_dir($full)) {
            $this->deleteDir($full);
        } else {
            unlink($full);
        }
        return back()->with('success', 'Hapus berhasil.');
    }

    public function download(Request $request, ?Hosting $hosting = null)
    {
        $hosting = $this->resolveHosting($hosting);
        $path = $this->cleanPath($request->query('path'));
        $full = $this->fullPath($hosting, $path);
        if (!is_file($full)) abort(404);
        return response()->download($full);
    }

    public function extract(Request $request, ?Hosting $hosting = null)
    {
        $hosting = $this->resolveHosting($hosting);
        $path = $this->cleanPath($request->post('path'));
        
        // Extract to current directory of the zip file
        $dir = dirname($path);
        if ($dir === '.') $dir = '';
        
        $full = $this->fullPath($hosting, $path);
        $extractTo = $this->fullPath($hosting, $dir);

        if (!is_file($full)) {
            return back()->with('error', 'File zip tidak ditemukan.');
        }

        if (strtolower(pathinfo($full, PATHINFO_EXTENSION)) !== 'zip') {
            return back()->with('error', 'Hanya file .zip yang bisa diekstrak.');
        }

        if (!class_exists('ZipArchive')) {
            return back()->with('error', 'Ekstensi PHP ZipArchive tidak terinstall di server.');
        }

        $zip = new \ZipArchive;
        if ($zip->open($full) === TRUE) {
            $zip->extractTo($extractTo);
            $zip->close();
            return back()->with('success', 'File zip berhasil diekstrak.');
        } else {
            return back()->with('error', 'Gagal membuka atau mengekstrak file zip.');
        }
    }

    public function bulkDelete(Request $request, ?Hosting $hosting = null)
    {
        $hosting = $this->resolveHosting($hosting);
        $paths = $request->post('paths'); // Array of relative paths
        if (!is_array($paths)) return back()->with('error', 'Tidak ada data yang dipilih.');

        $deleted = 0;
        foreach ($paths as $path) {
            $full = $this->fullPath($hosting, $this->cleanPath($path));
            if (file_exists($full)) {
                if (is_dir($full)) {
                    File::deleteDirectory($full);
                } else {
                    File::delete($full);
                }
                $deleted++;
            }
        }
        return back()->with('success', "$deleted item berhasil dihapus.");
    }

    public function paste(Request $request, ?Hosting $hosting = null)
    {
        $hosting = $this->resolveHosting($hosting);
        $mode = $request->post('mode'); // 'copy' or 'cut'
        $files = $request->post('files'); // Array of original relative paths
        $destinationDir = $this->cleanPath($request->post('destination', '')); // Current folder to paste into

        if (!is_array($files) || !in_array($mode, ['copy', 'cut'])) {
            return back()->with('error', 'Aksi tidak valid.');
        }

        $successCount = 0;
        $errors = [];

        foreach ($files as $file) {
            $srcRelative = $this->cleanPath($file);
            $srcFull = $this->fullPath($hosting, $srcRelative);

            if (!file_exists($srcFull)) continue;

            $basename = basename($srcFull);
            $destRelative = trim($destinationDir . '/' . $basename, '/');
            $destFull = $this->fullPath($hosting, $destRelative);

            // Skip if source and destination are the same
            if (realpath($srcFull) === realpath($destFull)) continue;

            // Prevent copying/moving a directory into itself or its subdirectories
            if (is_dir($srcFull) && strpos(realpath($destFull) ?: $destFull, realpath($srcFull) . DIRECTORY_SEPARATOR) === 0) {
                $errors[] = "$basename: tidak bisa paste ke dalam folder itu sendiri.";
                continue;
            }

            try {
                // Remove existing destination before overwrite
                if (file_exists($destFull)) {
                    if (is_dir($destFull)) File::deleteDirectory($destFull);
                    else File::delete($destFull);
                }

                // Ensure the destination parent directory exists
                File::ensureDirectoryExists(dirname($destFull), 0755, true);

                if ($mode === 'copy') {
                    if (is_dir($srcFull)) File::copyDirectory($srcFull, $destFull);
                    else File::copy($srcFull, $destFull);
                } else {
                    if (is_dir($srcFull)) File::moveDirectory($srcFull, $destFull);
                    else File::move($srcFull, $destFull);
                }

                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "$basename: " . $e->getMessage();
            }
        }

        if ($successCount === 0 && !empty($errors)) {
            return back()->with('error', 'Gagal: ' . implode('; ', $errors));
        }

        $msg = $mode === 'copy'
            ? "$successCount item berhasil disalin."
            : "$successCount item berhasil dipindahkan.";

        if (!empty($errors)) {
            $msg .= ' Beberapa item gagal: ' . implode('; ', $errors);
        }

        return back()->with('success', $msg);

    }

    private function fullPath(Hosting $hosting, string $rel): string
    {
        $base = storage_path('app/public/' . $hosting->path);
        if ($rel === '' || $rel === '.') return $base;
        return $base . '/' . ltrim($rel, '/');
    }

    private function cleanPath(?string $path): string
    {
        if (!$path) return '';
        $path = str_replace(['..', '\\'], '', $path);
        $path = trim($path, '/');
        // remove leading slash and null bytes
        $path = str_replace("\0", '', $path);
        return $path;
    }

    private function sanitize(string $name): string
    {
        $name = trim($name);
        $name = str_replace(['/', '\\', '..'], '', $name);
        // allow only safe chars
        $name = preg_replace('/[^a-zA-Z0-9\-\_\.\s]/', '', $name);
        return $name ?: 'untitled';
    }

    private function breadcrumbs(string $rel): array
    {
        if (!$rel) return [['name'=>'/','path'=>'']];
        $parts = explode('/', $rel);
        $crumbs = [['name'=>'/','path'=>'']];
        $acc = '';
        foreach ($parts as $p) {
            $acc = $acc ? $acc . '/' . $p : $p;
            $crumbs[] = ['name'=>$p, 'path'=>$acc];
        }
        return $crumbs;
    }

    private function formatBytes(int $b): string
    {
        if ($b < 1024) return $b.' B';
        if ($b < 1048576) return round($b/1024,1).' KB';
        if ($b < 1073741824) return round($b/1048576,1).' MB';
        return round($b/1073741824,2).' GB';
    }

    private function deleteDir(string $dir): void
    {
        $items = array_diff(scandir($dir), ['.','..']);
        foreach ($items as $it) {
            $p = $dir.'/'.$it;
            is_dir($p) ? $this->deleteDir($p) : unlink($p);
        }
        rmdir($dir);
    }
}
