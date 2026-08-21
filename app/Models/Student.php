<?php

namespace App\Models;

use App\Traits\HasHashId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class Student extends Authenticatable
{
    use HasFactory, Notifiable, HasHashId;

    protected $fillable = ['nim', 'name', 'github_url', 'linkedin_url', 'portfolio_url', 'photo', 'password'];
    protected $hidden = ['password', 'remember_token'];
    protected $appends = ['photo_url'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Untuk login pakai NIM (bukan email)
    public function getAuthIdentifierName()
    {
        return 'nim';
    }

    public function hosting()
    {
        return $this->hasOne(Hosting::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo) return null;
        if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
            return $this->photo;
        }
        return Storage::disk('public')->url($this->photo);
    }
}
