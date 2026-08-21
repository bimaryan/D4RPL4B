<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Support\WebpConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        return view('admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.galleries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'caption' => 'nullable|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'image_url' => 'nullable|url|max:512',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = WebpConverter::store($request->file('image'), 'galleries', 1600, 82);
        } elseif (!empty($validated['image_url'])) {
            $validated['image'] = $validated['image_url'];
        } else {
            return back()->withErrors(['image' => 'Gambar wajib diisi (upload file atau URL).'])->withInput();
        }
        unset($validated['image_url']);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Gallery::create($validated);
        return redirect()->route('galleries.index')->with('success', 'Foto gallery berhasil ditambahkan.');
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.galleries.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'caption' => 'nullable|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'image_url' => 'nullable|url|max:512',
            'sort_order' => 'nullable|integer|min:0|max:999',
            'remove_image' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($gallery->image && !str_starts_with($gallery->image, 'http')) {
                Storage::disk('public')->delete($gallery->image);
            }
            $validated['image'] = WebpConverter::store($request->file('image'), 'galleries', 1600, 82);
        } elseif (!empty($validated['image_url'])) {
            if ($gallery->image && !str_starts_with($gallery->image, 'http') && $validated['image_url'] !== $gallery->image) {
                Storage::disk('public')->delete($gallery->image);
            }
            $validated['image'] = $validated['image_url'];
        } elseif ($request->boolean('remove_image')) {
            return back()->withErrors(['image' => 'Gambar tidak boleh kosong.'])->withInput();
        } else {
            unset($validated['image']);
        }
        unset($validated['image_url'], $validated['remove_image']);
        $validated['sort_order'] = $validated['sort_order'] ?? $gallery->sort_order;

        $gallery->update($validated);
        return redirect()->route('galleries.index')->with('success', 'Foto gallery berhasil diupdate.');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->image && !str_starts_with($gallery->image, 'http')) {
            Storage::disk('public')->delete($gallery->image);
        }
        $gallery->delete();
        return redirect()->route('galleries.index')->with('success', 'Foto gallery berhasil dihapus.');
    }
}
