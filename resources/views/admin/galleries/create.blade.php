@extends('layouts.admin')
@section('title', 'Tambah Foto')
@section('breadcrumb', 'Gallery / Tambah')
@section('content')
<div class="max-w-[960px]">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('galleries.index') }}" class="w-9 h-9 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center hover:bg-[#F6F5F1]"><i class="fa-solid fa-arrow-left text-[12px]"></i></a>
        <div><h1 class="font-semibold text-[18px] leading-none">Tambah Foto Gallery</h1><p class="text-[12.5px] text-[#7A7670]">Upload file (disarankan 800x600 min) — auto WebP.</p></div>
    </div>
    <div class="grid lg:grid-cols-[1fr_300px] gap-6">
        <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E8DFD1] bg-[#FCFBF9] flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-[#11100F] text-white flex items-center justify-center"><i class="fa-solid fa-images text-[11px]"></i></span>
                <span class="font-medium text-[13px]">Form Gallery</span>
                <span class="ml-auto font-mono text-[11px] bg-amber-50 border border-amber-200 text-amber-700 rounded-full px-2 py-1">Wajib *</span>
            </div>
            <form action="{{ route('galleries.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                @if($errors->any())<div class="rounded-xl bg-red-50 border border-red-200 text-red-700 text-[12px] px-4 py-3"><ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Judul *</label><input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Polindra Tech Fest" class="form-input"></div>
                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Caption</label><input type="text" name="caption" value="{{ old('caption') }}" placeholder="Hackathon 24 jam • Juara 1" class="form-input"></div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Upload Foto *</label>
                        <label class="flex items-center gap-3 border-2 border-dashed border-[#E8DFD1] rounded-xl p-4 bg-[#FCFBF9] hover:bg-white cursor-pointer">
                            <span class="w-10 h-10 rounded-lg bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-cloud-arrow-up text-[#7A7670]"></i></span>
                            <div class="flex-1 min-w-0"><div class="text-[13px] font-medium">Pilih file</div><div class="text-[11px] text-[#7A7670] truncate" id="g-file">Belum ada file</div></div>
                            <input type="file" name="image" accept="image/*" class="hidden" onchange="document.getElementById('g-file').textContent=this.files[0]?this.files[0].name:'Belum ada file'">
                        </label>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Atau Image URL</label>
                        <input type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://..." class="form-input">
                    </div>
                </div>
                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Urutan</label><input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-input w-28"></div>
                <div class="flex justify-end gap-2 pt-2 border-t border-[#E8DFD1]">
                    <a href="{{ route('galleries.index') }}" class="px-5 py-2.5 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-medium hover:bg-[#F6F5F1]">Batal</a>
                    <button class="px-6 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black transition"><i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan</button>
                </div>
            </form>
        </div>
        <div class="space-y-4">
            <div class="bg-[#11100F] text-white rounded-xl p-5">
                <div class="font-medium text-[13px] flex items-center gap-2"><i class="fa-solid fa-lightbulb text-amber-400"></i> Tips Gallery</div>
                <ul class="text-[12.5px] leading-relaxed text-white/70 mt-2 space-y-1.5 list-disc list-inside">
                    <li>Urutan kecil tampil pertama di landing.</li>
                    <li>Foto pertama jadi large, terakhir jadi wide.</li>
                    <li>Auto WebP max 1600px.</li>
                </ul>
            </div>
            <div class="bg-white border border-[#E8DFD1] rounded-xl p-5">
                <div class="text-[11px] font-semibold tracking-[0.08em] uppercase text-[#7A7670]">Pratinjau</div>
                <div class="mt-3 aspect-[4/3] bg-[#F6F5F1] border border-dashed border-[#E8DFD1] rounded-lg flex flex-col items-center justify-center text-[#A8A29E] gap-2">
                    <i class="fa-regular fa-images text-[20px]"></i>
                    <span class="text-[11px]">4:3 • Gallery</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
