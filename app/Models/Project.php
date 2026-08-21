<?php

namespace App\Models;

use App\Traits\HasHashId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    use HasHashId;

    protected $fillable = ['title', 'description', 'image_url', 'tech_stack', 'demo_url', 'repo_url', 'portfolio_path', 'portfolio_index'];
    
    protected $casts = [
        'tech_stack' => 'array',
    ];

    protected $appends = ['image_src', 'portfolio_url'];

    public function getImageSrcAttribute(): ?string
    {
        if (!$this->image_url) return null;
        if (str_starts_with($this->image_url, 'http://') || str_starts_with($this->image_url, 'https://')) {
            return $this->image_url;
        }
        return Storage::disk('public')->url($this->image_url);
    }

    public function getPortfolioUrlAttribute(): ?string
    {
        if (!$this->portfolio_path) return null;
        return url('/portfolio/' . $this->hash_id);
    }
}
