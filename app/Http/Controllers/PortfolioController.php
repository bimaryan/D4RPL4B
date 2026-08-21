<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PortfolioController extends Controller
{
    public function show(Request $request, string $hash, string $path = null)
    {
        $project = $this->resolveProject($hash);
        if (!$project || !$project->portfolio_path) {
            abort(404, 'Portfolio tidak ditemukan');
        }

        $base = storage_path('app/public/' . $project->portfolio_path);

        // Default to index
        if (!$path || $path === '') {
            $path = $project->portfolio_index ?? 'index.html';
        }

        // Security: prevent directory traversal
        $path = str_replace('..', '', $path);
        $full = $base . '/' . ltrim($path, '/');

        // Jika request folder tanpa file, coba index.html
        if (is_dir($full)) {
            $full = rtrim($full, '/') . '/index.html';
        }

        if (!file_exists($full) || !is_file($full)) {
            // Fallback ke index.html untuk SPA
            $fallback = $base . '/' . ($project->portfolio_index ?? 'index.html');
            if (file_exists($fallback)) {
                $full = $fallback;
            } else {
                abort(404);
            }
        }

        $mime = mime_content_type($full) ?: 'application/octet-stream';
        // Fix mime for common web files
        $ext = strtolower(pathinfo($full, PATHINFO_EXTENSION));
        $map = [
            'html' => 'text/html',
            'htm' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'mjs' => 'application/javascript',
            'json' => 'application/json',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'mp4' => 'video/mp4',
        ];
        if (isset($map[$ext])) $mime = $map[$ext];

        return response()->file($full, ['Content-Type' => $mime, 'Cache-Control' => 'public, max-age=3600']);
    }

    private function resolveProject(string $hash): ?Project
    {
        // Use hashId trait resolver
        $tmp = new Project();
        return $tmp->resolveRouteBinding($hash);
    }
}
