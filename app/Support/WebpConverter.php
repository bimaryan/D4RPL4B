<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WebpConverter
{
    public static function store(UploadedFile $file, string $directory, int $maxWidth = 1600, int $quality = 82): string
    {
        $tmpPath = $file->getPathname();
        $data = file_get_contents($tmpPath);
        $src = @imagecreatefromstring($data);

        // Fallback: jika GD gagal (misal SVG), simpan asli tapi ganti ekstensi webp? Simpan asli aja
        if (!$src) {
            $filename = Str::uuid()->toString() . '.webp';
            $path = trim($directory, '/') . '/' . $filename;
            Storage::disk('public')->put($path, $data);
            return $path;
        }

        $width = imagesx($src);
        $height = imagesy($src);

        // Resize jika > maxWidth
        if ($width > $maxWidth) {
            $newHeight = (int) round($height * ($maxWidth / $width));
            $resized = imagescale($src, $maxWidth, $newHeight, IMG_BICUBIC);
            imagedestroy($src);
            $src = $resized;
            $width = $maxWidth;
            $height = $newHeight;
        }

        // Pastikan truecolor dengan alpha
        if (!imageistruecolor($src)) {
            $true = imagecreatetruecolor($width, $height);
            imagealphablending($true, false);
            imagesavealpha($true, true);
            $transparent = imagecolorallocatealpha($true, 255, 255, 255, 127);
            imagefilledrectangle($true, 0, 0, $width, $height, $transparent);
            imagecopy($true, $src, 0, 0, 0, 0, $width, $height);
            imagedestroy($src);
            $src = $true;
        } else {
            imagealphablending($src, false);
            imagesavealpha($src, true);
        }

        ob_start();
        imagewebp($src, null, $quality);
        $webpData = ob_get_clean();
        imagedestroy($src);

        $filename = Str::uuid()->toString() . '.webp';
        $path = trim($directory, '/') . '/' . $filename;
        Storage::disk('public')->put($path, $webpData);

        return $path;
    }
}
