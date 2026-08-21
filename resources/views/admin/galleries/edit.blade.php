@extends('layouts.admin')
@section('title', 'Edit Foto')
@section('breadcrumb', 'Gallery / Edit')
@section('content')
<div class="max-w-[960px]">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('galleries.index') }}" class="w-9 h-9 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center hover:bg-[#F6F5F1]"><i class="fa-solid fa-arrow-left text-[12px]"></i></a>
        <div class="flex-1"><h1 class="font-semibold text-[18px] leading-none">Edit Foto</h1><p class="font-mono text-[11px] text-[#7A7670]">{{ $gallery->hash_id }}</p></div>
        <form action="{{ route('galleries.destroy', $gallery->hash_id) }}" method="POST" onsubmit="return confirmDelete(this, 'Hapus Data?', 'Hapus foto ini? Yakin ingin melanjutkan? Tindakan tidak bisa dibatalkan')">@csrf @method('DELETE')<button class="px-4 py-2 rounded-full bg-white border border-red-200 text-red-600 text-[12px] font-medium hover:bg-red-50"><i class="fa-solid fa-trash mr-1"></i> Hapus</button></form>
    </div>
    <div class="grid lg:grid-cols-[1fr_300px] gap-6">
        <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E8DFD1] bg-[#FCFBF9] flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-images text-[11px]"></i></span>
                <span class="font-medium text-[13px]">Edit Foto</span>
            </div>
            <form action="{{ route('galleries.update', $gallery->hash_id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf @method('PUT')
                @if($errors->any())<div class="rounded-xl bg-red-50 border border-red-200 text-red-700 text-[12px] px-4 py-3"><ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Judul *</label><input type="text" name="title" value="{{ old('title', $gallery->title) }}" required class="form-input"></div>
                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Caption</label><input type="text" name="caption" value="{{ old('caption', $gallery->caption) }}" class="form-input"></div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Ganti Foto</label>
                        <label class="flex items-center gap-3 border-2 border-dashed border-[#E8DFD1] rounded-xl p-3 bg-[#FCFBF9] hover:bg-white cursor-pointer">
                            <span class="w-9 h-9 rounded-lg bg-white border flex items-center justify-center"><i class="fa-solid fa-camera text-[11px]"></i></span>
                            <div class="flex-1"><div class="text-[12px] font-medium">Upload baru</div><div class="text-[11px] text-[#7A7670] truncate" id="g-edit">Belum ada file baru</div></div>
                            <input type="file" name="image" accept="image/*" class="hidden" onchange="document.getElementById('g-edit').textContent=this.files[0]?this.files[0].name:'Belum ada file baru'">
                        </label>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Atau URL</label>
                        <input type="url" name="image_url" value="{{ old('image_url', str_starts_with($gallery->image, 'http') ? $gallery->image : '') }}" placeholder="https://..." class="form-input">
                    </div>
                </div>
                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Urutan</label><input type="number" name="sort_order" value="{{ old('sort_order', $gallery->sort_order) }}" class="form-input w-28"></div>
                <div class="flex justify-end gap-2 pt-2 border-t border-[#E8DFD1]">
                    <a href="{{ route('galleries.index') }}" class="px-5 py-2.5 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-medium hover:bg-[#F6F5F1]">Batal</a>
                    <button class="px-6 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black transition"><i class="fa-solid fa-floppy-disk mr-1.5"></i> Update</button>
                </div>
            </form>
        </div>
        <div class="space-y-4">
            <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
                <img src="{{ $gallery->image_url }}" class="w-full aspect-[4/3] object-cover">
                <div class="p-4"><div class="font-medium text-[13px]">{{ $gallery->title }}</div><div class="text-[11px] text-[#7A7670]">{{ $gallery->caption }}</div><div class="font-mono text-[10px] mt-1 text-[#7A7670]">{{ substr($gallery->hash_id,0,10) }}</div></div>
            </div>
            <div class="bg-[#11100F] text-white rounded-xl p-5">
                <div class="text-[13px] font-medium flex items-center gap-2"><i class="fa-solid fa-lightbulb text-amber-400"></i> Tips</div>
                <p class="text-[12px] text-white/70 mt-1">Foto pertama jadi large di landing, terakhir jadi wide. Urutan kecil di atas.</p>
            </div>
        </div>
    </div>
</div>
@endsection
