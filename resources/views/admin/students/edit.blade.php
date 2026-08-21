@extends('layouts.admin')
@section('title', 'Edit Mahasiswa')
@section('breadcrumb', 'Mahasiswa / Edit')
@section('content')
<div class="max-w-[960px]">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('students.index') }}" class="w-9 h-9 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center hover:bg-[#F6F5F1]"><i class="fa-solid fa-arrow-left text-[12px]"></i></a>
        <div class="flex-1">
            <h1 class="font-semibold text-[18px] leading-none">Edit Mahasiswa</h1>
            <p class="text-[12.5px] text-[#7A7670] flex items-center gap-2">Hash: <span class="font-mono bg-[#F6F5F1] border border-[#E8DFD1] rounded-full px-2 py-0.5 text-[11px]">{{ $student->hash_id }}</span> • {{ $student->nim }}</p>
        </div>
        <form action="{{ route('students.destroy', $student->hash_id) }}" method="POST" onsubmit="return confirmDelete(this, 'Hapus Data?', 'Hapus {{ $student->name }}? Yakin ingin melanjutkan? Tindakan tidak bisa dibatalkan')">
            @csrf @method('DELETE')
            <button class="px-4 py-2 rounded-full bg-white border border-red-200 text-red-600 text-[12px] font-medium hover:bg-red-50"><i class="fa-solid fa-trash mr-1"></i> Hapus</button>
        </form>
    </div>

    <div class="grid lg:grid-cols-[1fr_300px] gap-6">
        <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E8DFD1] bg-[#FCFBF9] flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-pen text-[11px]"></i></span>
                <span class="font-medium text-[13px]">Edit Data</span>
            </div>
            <form action="{{ route('students.update', $student->hash_id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf @method('PUT')
                @if ($errors->any())
                    <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 text-[12.5px] px-4 py-3"><div class="font-semibold flex items-center gap-2"><i class="fa-solid fa-circle-exclamation"></i> Periksa kembali</div><ul class="list-disc list-inside mt-1">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                @endif
                <div class="grid md:grid-cols-2 gap-4">
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">NIM *</label><input type="text" name="nim" value="{{ old('nim', $student->nim) }}" required class="form-input font-mono"></div>
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Nama Lengkap *</label><input type="text" name="name" value="{{ old('name', $student->name) }}" required class="form-input"></div>
                </div>

                <div class="grid md:grid-cols-2 gap-4 items-end">
                    <div>
                        <label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Ganti Foto</label>
                        <label class="flex items-center gap-3 border border-dashed border-[#E8DFD1] rounded-xl p-3 bg-[#FCFBF9] hover:bg-white cursor-pointer">
                            <span class="w-10 h-10 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-camera text-[#7A7670]"></i></span>
                            <div class="flex-1 min-w-0">
                                <div class="text-[13px] font-medium">Upload baru</div>
                                <div class="text-[11px] text-[#7A7670] truncate" id="photo-name">Belum ada file baru</div>
                            </div>
                            <input type="file" name="photo" accept="image/*" class="hidden" onchange="document.getElementById('photo-name').textContent = this.files[0] ? this.files[0].name : 'Belum ada file baru'">
                        </label>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Atau Foto URL</label>
                        <input type="url" name="photo_url" value="{{ old('photo_url') }}" placeholder="https://..." class="form-input">
                    </div>
                </div>
                <label class="flex items-center gap-2 text-[12px]"><input type="checkbox" name="remove_photo" value="1" class="rounded border-[#E8DFD1]"> Hapus foto (kembali ke inisial)</label>

                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">GitHub URL</label><div class="relative"><span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#7A7670]"><i class="fa-brands fa-github text-[13px]"></i></span><input type="url" name="github_url" value="{{ old('github_url', $student->github_url) }}" class="form-input pl-9"></div></div>
                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">LinkedIn URL</label><div class="relative"><span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#0A66C2]"><i class="fa-brands fa-linkedin text-[13px]"></i></span><input type="url" name="linkedin_url" value="{{ old('linkedin_url', $student->linkedin_url) }}" class="form-input pl-9"></div></div>
                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Portfolio URL</label><div class="relative"><span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#7A7670]"><i class="fa-solid fa-link text-[12px]"></i></span><input type="url" name="portfolio_url" value="{{ old('portfolio_url', $student->portfolio_url) }}" class="form-input pl-9"></div></div>
                <div class="flex items-center justify-end gap-2 pt-2 border-t border-[#E8DFD1]">
                    <a href="{{ route('students.index') }}" class="px-5 py-2.5 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-medium">Batal</a>
                    <button class="px-6 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black"><i class="fa-solid fa-floppy-disk mr-1.5"></i> Update</button>
                </div>
            </form>
        </div>
        <div class="space-y-4">
            <div class="bg-white border border-[#E8DFD1] rounded-xl p-5">
                <div class="text-[11px] font-semibold tracking-[0.08em] uppercase text-[#7A7670]">Foto Saat Ini</div>
                <div class="mt-3 flex flex-col items-center gap-3">
                    <img src="{{ $student->photo_url ?? 'https://api.dicebear.com/7.x/initials/svg?seed='.urlencode($student->name).'&backgroundColor=F5EFE6' }}" class="w-24 h-24 rounded-full border-2 border-[#E8DFD1] object-cover bg-white">
                    <div class="text-center"><div class="font-medium text-[13px]">{{ $student->name }}</div><div class="font-mono text-[11px] text-[#7A7670]">{{ $student->nim }}</div></div>
                    @if($student->photo)<div class="font-mono text-[10px] bg-[#F6F5F1] border border-[#E8DFD1] rounded-full px-2 py-1 truncate max-w-[200px]">{{ $student->photo }}</div>@else<div class="text-[11px] text-[#7A7670]">Pakai inisial</div>@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
