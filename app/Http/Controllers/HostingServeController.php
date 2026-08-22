<?php

namespace App\Http\Controllers;

use App\Models\Hosting;
use Illuminate\Http\Request;

class HostingServeController extends Controller
{
    public function serveSubdomain(Request $request, string $subdomain, string $any = null)
    {
        $host = $request->getHost();
        $hosting = Hosting::where('domain', $host)->first();

        if (!$hosting || $hosting->status !== 'active') {
            abort(404, 'Hosting tidak ditemukan atau tidak aktif');
        }

        return $this->serveFiles($request, $hosting, $any);
    }

    public function show(Request $request, string $hash, string $any = null)
    {
        $hosting = $this->resolve($hash);
        if (!$hosting || $hosting->status !== 'active') abort(404, 'Hosting tidak aktif');

        return $this->serveFiles($request, $hosting, $any);
    }

    private function serveFiles(Request $request, Hosting $hosting, ?string $any)
    {

        $path = $any ?? $request->query('path', '');
        // Normalize
        $path = ltrim($path ?? '', '/');
        $base = storage_path('app/public/' . $hosting->path);
        $full = $path ? $base . '/' . $path : $base . '/index.html';

        // If path is directory, try index
        if (is_dir($full)) {
            $full = rtrim($full, '/') . '/index.html';
        }

        // Security: prevent traversal
        $realBase = realpath($base);
        $realFull = realpath($full) ?: $full;
        if ($realBase && str_starts_with($realFull, $realBase) === false && file_exists($full)) {
            abort(403);
        }

        if (!file_exists($full) || !is_file($full)) {
            // SPA fallback: serve index.html if exists
            $fallback = $base . '/index.html';
            if (file_exists($fallback)) {
                $full = $fallback;
            } else {
                abort(404, 'File tidak ditemukan: ' . htmlspecialchars($path));
            }
        }

        $mime = mime_content_type($full) ?: 'text/plain';
        $ext = strtolower(pathinfo($full, PATHINFO_EXTENSION));
        $map = ['html'=>'text/html','htm'=>'text/html','css'=>'text/css','js'=>'application/javascript','mjs'=>'application/javascript','json'=>'application/json','png'=>'image/png','jpg'=>'image/jpeg','jpeg'=>'image/jpeg','webp'=>'image/webp','svg'=>'image/svg+xml','gif'=>'image/gif','ico'=>'image/x-icon','woff'=>'font/woff','woff2'=>'font/woff2','ttf'=>'font/ttf','mp4'=>'video/mp4','pdf'=>'application/pdf'];
        if (isset($map[$ext])) $mime = $map[$ext];

        return response()->file($full, ['Content-Type'=>$mime, 'Cache-Control'=>'no-cache, no-store, must-revalidate', 'Pragma'=>'no-cache', 'Expires'=>'0', 'X-Hosting'=>$hosting->hash_id]);
    }

    private function resolve(string $hash): ?Hosting
    {
        $tmp = new Hosting();
        return $tmp->resolveRouteBinding($hash);
    }
}
