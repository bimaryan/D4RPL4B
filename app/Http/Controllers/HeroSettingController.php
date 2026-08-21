<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Support\WebpConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSettingController extends Controller
{
    public function edit()
    {
        $heroImage = Setting::heroImageUrl();
        $rawValue = Setting::get('hero_image');
        $heroCaption = Setting::heroCaption();
        return view('admin.hero.edit', compact('heroImage', 'rawValue', 'heroCaption'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,avif|max:4096',
            'hero_image_url' => 'nullable|url|max:512',
            'remove_image' => 'nullable|boolean',
            'hero_caption' => 'nullable|string|max:100',
        ]);

        $current = Setting::get('hero_image');

        if ($request->hasFile('hero_image')) {
            if ($current && !str_starts_with($current, 'http')) {
                Storage::disk('public')->delete($current);
            }
            $path = WebpConverter::store($request->file('hero_image'), 'hero', 1600, 82);
            Setting::set('hero_image', $path);
        } elseif (!empty($validated['hero_image_url'])) {
            if ($current && !str_starts_with($current, 'http')) {
                Storage::disk('public')->delete($current);
            }
            Setting::set('hero_image', $validated['hero_image_url']);
        } elseif ($request->boolean('remove_image')) {
            if ($current && !str_starts_with($current, 'http')) {
                Storage::disk('public')->delete($current);
            }
            Setting::set('hero_image', 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&q=80&w=900');
        }

        if (isset($validated['hero_caption'])) {
            Setting::set('hero_caption', $validated['hero_caption']);
        }

        return redirect()->route('hero.edit')->with('success', 'Hero image berhasil diupdate. Cek landing page.');
    }
}
