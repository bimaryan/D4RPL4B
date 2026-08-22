<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hosting;
use Illuminate\Http\Request;

class HostingCpanelController extends Controller
{
    private function resolve(string $hash): Hosting
    {
        $tmp = new Hosting();
        $hosting = $tmp->resolveRouteBinding($hash);
        if (!$hosting) abort(404);
        return $hosting;
    }

    public function metrics(string $hash)
    {
        $hosting = $this->resolve($hash);
        $usage = $hosting->diskUsage();
        return view('admin.hostings.metrics', compact('hosting', 'usage'));
    }

    public function backup(string $hash)
    {
        $hosting = $this->resolve($hash);
        $path = storage_path('app/public/' . $hosting->path);
        
        if (!is_dir($path)) {
            return back()->with('error', 'Folder hosting tidak ditemukan.');
        }

        $zipFileName = 'backup_' . $hosting->student->nim . '_' . date('Ymd_His') . '.zip';
        $zipPath = storage_path('app/' . $zipFileName);

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::LEAVES_ONLY);
            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($path) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function ssl(string $hash)
    {
        $hosting = $this->resolve($hash);
        return view('admin.hostings.ssl', compact('hosting'));
    }

    public function terminal(string $hash)
    {
        $hosting = $this->resolve($hash);
        return view('admin.hostings.terminal', compact('hosting'));
    }

    public function terminalExecute(Request $request, string $hash)
    {
        $hosting = $this->resolve($hash);
        $cmd = $request->input('command');
        
        if (empty(trim($cmd))) {
            return response()->json(['output' => '']);
        }

        $path = storage_path('app/public/' . $hosting->path);
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        // Basic protection: prevent some very dangerous commands
        if (preg_match('/(rm\s+-rf\s+\/|mkfs|dd\s+if=)/i', $cmd)) {
            return response()->json(['output' => 'Command blocked for security reasons.']);
        }

        $fullCmd = "cd " . escapeshellarg($path) . " && " . $cmd . " 2>&1";
        $output = shell_exec($fullCmd);

        return response()->json(['output' => $output ?? '']);
    }

    public function database(string $hash)
    {
        $hosting = $this->resolve($hash);
        $databases = $hosting->databases;
        return view('admin.hostings.database', compact('hosting', 'databases'));
    }

    public function databaseCreate(Request $request, string $hash)
    {
        $hosting = $this->resolve($hash);
        
        $dbName = 'db_' . strtolower($hosting->student->nim);
        $dbUser = 'usr_' . strtolower($hosting->student->nim);
        $dbPass = \Illuminate\Support\Str::random(12);

        try {
            \Illuminate\Support\Facades\DB::connection('mysql_root')->statement("CREATE DATABASE IF NOT EXISTS `{$dbName}`");
            
            // Periksa apakah user sudah ada
            $userExists = \Illuminate\Support\Facades\DB::connection('mysql_root')->select("SELECT User FROM mysql.user WHERE User = ?", [$dbUser]);
            
            if (empty($userExists)) {
                \Illuminate\Support\Facades\DB::connection('mysql_root')->statement("CREATE USER '{$dbUser}'@'%' IDENTIFIED BY '{$dbPass}'");
                \Illuminate\Support\Facades\DB::connection('mysql_root')->statement("GRANT ALL PRIVILEGES ON `{$dbName}`.* TO '{$dbUser}'@'%'");
                \Illuminate\Support\Facades\DB::connection('mysql_root')->statement("FLUSH PRIVILEGES");
            } else {
                // Update password jika user sudah ada
                \Illuminate\Support\Facades\DB::connection('mysql_root')->statement("ALTER USER '{$dbUser}'@'%' IDENTIFIED BY '{$dbPass}'");
            }

            $hosting->databases()->create([
                'db_name' => $dbName,
                'db_user' => $dbUser,
                'db_password' => $dbPass,
                'status' => 'active'
            ]);

            return back()->with('success', 'Database berhasil dibuat!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat database: ' . $e->getMessage());
        }
    }

    public function cron(string $hash)
    {
        $hosting = $this->resolve($hash);
        $cronJobs = $hosting->cronJobs;
        return view('admin.hostings.cron', compact('hosting', 'cronJobs'));
    }

    public function cronStore(Request $request, string $hash)
    {
        $request->validate([
            'url' => 'required|url',
            'interval' => 'required|in:everyMinute,everyFiveMinutes,hourly,daily'
        ]);

        $hosting = $this->resolve($hash);
        $hosting->cronJobs()->create([
            'url' => $request->input('url'),
            'interval' => $request->input('interval'),
            'status' => 'active'
        ]);

        return back()->with('success', 'Cron job berhasil ditambahkan.');
    }

    public function cronDestroy(string $hash)
    {
        $cronJob = \App\Models\HostingCronJob::findOrFail($hash); // hash here is actually ID for simplicity in route, wait, I defined it as {id} in web.php
        $cronJob->delete();
        return back()->with('success', 'Cron job dihapus.');
    }
}
