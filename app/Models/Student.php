<?php

namespace App\Models;

use App\Traits\HasHashId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Student extends Model
{
    use HasHashId;

    protected $fillable = ['nim', 'name', 'github_url', 'linkedin_url', 'portfolio_url', 'photo'];
    protected $appends = ['photo_url'];

    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo) return null;
        if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
            return $this->photo;
        }
        return Storage::disk('public')->url($this->photo);
    }
}
