<?php

namespace App\Http\Controllers;

use App\Models\Hosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileManagerController extends Controller
{
    public function index(Request $request, Hosting $hosting)
    {
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

        return view('admin.hostings.filemanager', compact('hosting', 'items', 'rel', 'breadcrumbs', 'usage'));
    }

    public function upload(Request $request, Hosting $hosting)
    {
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

    public function mkdir(Request $request, Hosting $hosting)
    {
        $request->validate(['name'=>'required|string|max:100', 'path'=>'nullable|string']);
        $rel = $this->cleanPath($request->input('path',''));
        $name = $this->sanitize($request->input('name'));
        $full = $this->fullPath($hosting, $rel) . '/' . $name;
        if (!is_dir($full)) mkdir($full, 0755, true);
        return back()->with('success', "Folder $name dibuat.");
    }

    public function mkfile(Request $request, Hosting $hosting)
    {
        $request->validate(['name'=>'required|string|max:100', 'path'=>'nullable|string', 'content'=>'nullable|string']);
        $rel = $this->cleanPath($request->input('path',''));
        $name = $this->sanitize($request->input('name'));
        $full = $this->fullPath($hosting, $rel) . '/' . $name;
        file_put_contents($full, $request->input('content',''));
        return back()->with('success', "File $name dibuat.");
    }

    public function edit(Request $request, Hosting $hosting)
    {
        $path = $this->cleanPath($request->query('path'));
        $full = $this->fullPath($hosting, $path);
        if (!is_file($full)) abort(404);
        $content = file_get_contents($full);
        // Prevent huge files
        if (strlen($content) > 500000) $content = substr($content,0,500000) . "\n\n... truncated ...";
        return response()->json(['content'=>$content, 'path'=>$path, 'name'=>basename($path)]);
    }

    public function update(Request $request, Hosting $hosting)
    {
        $request->validate(['path'=>'required|string', 'content'=>'nullable|string']);
        $path = $this->cleanPath($request->input('path'));
        $full = $this->fullPath($hosting, $path);
        if (!is_file($full)) abort(404);
        file_put_contents($full, $request->input('content',''));
        return back()->with('success', 'File disimpan.');
    }

    public function rename(Request $request, Hosting $hosting)
    {
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

    public function destroy(Request $request, Hosting $hosting)
    {
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

    public function download(Request $request, Hosting $hosting)
    {
        $path = $this->cleanPath($request->query('path'));
        $full = $this->fullPath($hosting, $path);
        if (!is_file($full)) abort(404);
        return response()->download($full);
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
