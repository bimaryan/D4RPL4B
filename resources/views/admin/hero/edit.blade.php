@extends('layouts.admin')
@section('title', 'Hero Section')
@section('breadcrumb', 'Hero')
@section('content')
<div class="w-full">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="w-9 h-9 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center hover:bg-[#F6F5F1]"><i class="fa-solid fa-arrow-left text-[12px]"></i></a>
        <div>
            <h1 class="font-semibold text-[18px] leading-none">Hero — Gambar Section Pertama</h1>
            <p class="text-[12.5px] text-[#7A7670]">Ganti gambar hero landing (kanan atas). Otomatis jadi WebP biar ngebut.</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-[1fr_300px] gap-6">
        <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E8DFD1] bg-[#FCFBF9] flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-image text-[11px]"></i></span>
                <span class="font-medium text-[13px]">Ganti Gambar Hero</span>
            </div>
            <form action="{{ route('hero.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf @method('PUT')
                @if($errors->any())<div class="rounded-xl bg-red-50 border border-red-200 text-red-700 text-[12px] px-4 py-3"><ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
                @if(session('success'))<div class="rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-[13px] px-4 py-3 flex items-center gap-2"><i class="fa-solid fa-check text-emerald-600"></i> {{ session('success') }}</div>@endif

                <div>
                    <label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Upload Gambar Baru <span class="font-normal">JPG/PNG/WebP max 4MB → auto WebP</span></label>
                    <label class="flex items-center gap-3 border-2 border-dashed border-[#E8DFD1] rounded-xl p-4 bg-[#FCFBF9] hover:bg-white cursor-pointer">
                        <span class="w-10 h-10 rounded-lg bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-cloud-arrow-up text-[#7A7670]"></i></span>
                        <div class="flex-1 min-w-0">
                            <div class="text-[13px] font-medium">Pilih / Drag file</div>
                            <div class="text-[11px] text-[#7A7670] truncate" id="hero-file">Belum ada file baru</div>
                        </div>
                        <input type="file" name="hero_image" accept="image/*" class="hidden" onchange="document.getElementById('hero-file').textContent=this.files[0]?this.files[0].name:'Belum ada file baru'">
                    </label>
                </div>

                <div>
                    <label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Atau URL Gambar</label>
                    <input type="url" name="hero_image_url" value="{{ old('hero_image_url') }}" placeholder="https://..." class="form-input">
                    <div class="text-[11px] text-[#7A7670] mt-1">Kosongkan jika upload. Prioritas file upload. Saat ini: <span class="font-mono text-[10px] bg-[#F6F5F1] border border-[#E8DFD1] rounded px-1.5 py-0.5 break-all">{{ $rawValue }}</span></div>
                </div>

                <div>
                    <label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Caption Gambar (Teks Pendek)</label>
                    <input type="text" name="hero_caption" value="{{ old('hero_caption', $heroCaption) }}" placeholder="Dok. Makrab 4B — Pantai Karangsong, 2025" class="form-input" maxlength="100">
                </div>

                <label class="flex items-center gap-2 text-[12px]"><input type="checkbox" name="remove_image" value="1" class="rounded"> Reset ke default</label>

                <div class="flex justify-end gap-2 pt-2 border-t border-[#E8DFD1]">
                    <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-medium">Batal</a>
                    <button class="px-6 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black"><i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Hero</button>
                </div>
            </form>
        </div>

        <div class="space-y-4">
            <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b border-[#E8DFD1] bg-[#FCFBF9] text-[11px] font-semibold tracking-[0.08em] uppercase text-[#7A7670]">Preview Saat Ini</div>
                <div class="aspect-[4/3] bg-[#F6F5F1] overflow-hidden">
                    <img src="{{ $heroImage }}" alt="Hero preview" class="w-full h-full object-cover">
                </div>
                <div class="p-3 text-[11px] font-mono text-[#7A7670] break-all">{{ $heroImage }}</div>
            </div>
            <div class="bg-[#11100F] text-white rounded-xl p-5">
                <div class="text-[13px] font-medium flex items-center gap-2"><i class="fa-solid fa-lightbulb text-amber-400"></i> Tips</div>
                <p class="text-[12px] text-white/70 mt-1">Upload foto kelas 4B ukuran 900x700 min, otomatis resize max 1600px & convert WebP quality 82 biar landing tetap ngebut.</p>
            </div>
        </div>
    </div>
</div>
@endsection
