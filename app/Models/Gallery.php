<?php

namespace App\Models;

use App\Traits\HasHashId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    use HasHashId;

    protected $fillable = ['title', 'caption', 'image', 'sort_order'];
    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) return null;
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }
        return Storage::disk('public')->url($this->image);
    }
}
