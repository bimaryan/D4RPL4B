<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function heroImageUrl(): string
    {
        $value = static::get('hero_image');
        if (!$value) {
            return 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&q=80&w=900';
        }
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        return Storage::disk('public')->url($value);
    }

    public static function heroCaption(): string
    {
        return static::get('hero_caption', 'Dok. Makrab 4B — Pantai Karangsong, 2025');
    }
}
