<?php

namespace App\Models;

use App\Traits\HasHashId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Hosting extends Model
{
    use HasHashId;

    protected $fillable = ['student_id', 'domain', 'path', 'status', 'quota_mb'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function getUrlAttribute(): string
    {
        return url('/hosting/' . $this->hash_id);
    }

    public function getDomainUrlAttribute(): ?string
    {
        return $this->domain ? 'https://' . $this->domain : $this->url;
    }

    public function diskUsage(): array
    {
        $full = storage_path('app/public/' . $this->path);
        $size = 0;
        $files = 0;
        if (is_dir($full)) {
            $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($full, \RecursiveDirectoryIterator::SKIP_DOTS));
            foreach ($it as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                    $files++;
                }
            }
        }
        return ['bytes' => $size, 'files' => $files, 'human' => $this->formatBytes($size)];
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes/1024,1) . ' KB';
        if ($bytes < 1073741824) return round($bytes/1048576,1) . ' MB';
        return round($bytes/1073741824,2) . ' GB';
    }
}
