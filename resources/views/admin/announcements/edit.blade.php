@extends('layouts.admin')
@section('title', 'Edit Pengumuman')
@section('breadcrumb', 'Pengumuman / Edit')
@section('content')
<div class="max-w-[960px]">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('announcements.index') }}" class="w-9 h-9 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-arrow-left text-[12px]"></i></a>
        <div class="flex-1"><h1 class="font-semibold text-[18px] leading-none">Edit Pengumuman</h1><p class="font-mono text-[11px] text-[#7A7670]">Hash: {{ $announcement->hash_id }}</p></div>
        <form action="{{ route('announcements.destroy', $announcement->hash_id) }}" method="POST" onsubmit="return confirmDelete(this, 'Hapus Data?', 'Hapus pengumuman? Yakin ingin melanjutkan? Tindakan tidak bisa dibatalkan')">@csrf @method('DELETE')<button class="px-4 py-2 rounded-full bg-white border border-red-200 text-red-600 text-[12px] font-medium hover:bg-red-50"><i class="fa-solid fa-trash mr-1"></i> Hapus</button></form>
    </div>
    <div class="grid lg:grid-cols-[1fr_300px] gap-6">
        <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E8DFD1] bg-[#FCFBF9] flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-pen text-[11px]"></i></span>
                <span class="font-medium text-[13px]">Edit Pengumuman</span>
            </div>
            <form action="{{ route('announcements.update', $announcement->hash_id) }}" method="POST" class="p-6 space-y-5">
                @csrf @method('PUT')
                @if ($errors->any()) <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 text-[12.5px] px-4 py-3"><ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div> @endif
                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Judul *</label><input type="text" name="title" value="{{ old('title', $announcement->title) }}" required class="form-input"></div>
                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Isi *</label><textarea name="content" required rows="4" class="form-input">{{ old('content', $announcement->content) }}</textarea></div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Kategori *</label><select name="category" required class="form-input"><option value="General" {{ old('category', $announcement->category) == 'General' ? 'selected' : '' }}>General</option><option value="Urgent Deadline" {{ old('category', $announcement->category) == 'Urgent Deadline' ? 'selected' : '' }}>Urgent Deadline</option><option value="Exam Info" {{ old('category', $announcement->category) == 'Exam Info' ? 'selected' : '' }}>Exam Info</option></select></div>
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Tanggal</label><input type="date" name="event_date" value="{{ old('event_date', $announcement->event_date ? \Carbon\Carbon::parse($announcement->event_date)->format('Y-m-d') : '') }}" class="form-input"></div>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-[#E8DFD1]">
                    <a href="{{ route('announcements.index') }}" class="px-5 py-2.5 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-medium">Batal</a>
                    <button class="px-6 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black"><i class="fa-solid fa-floppy-disk mr-1.5"></i> Update</button>
                </div>
            </form>
        </div>
        <div class="bg-white border border-[#E8DFD1] rounded-xl p-5">
            <div class="text-[11px] font-semibold tracking-[0.08em] uppercase text-[#7A7670]">Preview</div>
            <div class="mt-3 rounded-xl border p-3 {{ $announcement->category=='Urgent Deadline' ? 'bg-red-50 border-red-200' : 'bg-[#F6F5F1] border-[#E8DFD1]' }}">
                <div class="font-mono text-[10px] uppercase {{ $announcement->category=='Urgent Deadline' ? 'text-red-600' : 'text-[#7A7670]' }}">{{ $announcement->category }}</div>
                <div class="font-medium text-[13px] mt-1">{{ $announcement->title }}</div>
                <div class="text-[12px] text-[#7A7670] mt-1">{{ \Illuminate\Support\Str::limit($announcement->content,80) }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
