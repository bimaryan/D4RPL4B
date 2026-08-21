@extends('layouts.admin')
@section('title', 'Buat Pengumuman')
@section('breadcrumb', 'Pengumuman / Buat')
@section('content')
<div class="max-w-[960px]">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('announcements.index') }}" class="w-9 h-9 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-arrow-left text-[12px]"></i></a>
        <div><h1 class="font-semibold text-[18px] leading-none">Buat Pengumuman</h1><p class="text-[12.5px] text-[#7A7670]">Tampil di papan akademik landing.</p></div>
    </div>
    <div class="grid lg:grid-cols-[1fr_300px] gap-6">
        <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E8DFD1] bg-[#FCFBF9] flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-[#11100F] text-white flex items-center justify-center"><i class="fa-solid fa-bullhorn text-[11px]"></i></span>
                <span class="font-medium text-[13px]">Form Pengumuman</span>
            </div>
            <form action="{{ route('announcements.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                @if ($errors->any()) <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 text-[12.5px] px-4 py-3"><ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div> @endif
                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Judul *</label><input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: UTS AI — 20 Juni" class="form-input"></div>
                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Isi *</label><textarea name="content" required rows="4" placeholder="Detail pengumuman..." class="form-input">{{ old('content') }}</textarea></div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Kategori *</label><select name="category" required class="form-input"><option value="General">General</option><option value="Urgent Deadline">Urgent Deadline</option><option value="Exam Info">Exam Info</option></select></div>
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Tanggal Event</label><input type="date" name="event_date" value="{{ old('event_date') }}" class="form-input"></div>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-[#E8DFD1]">
                    <a href="{{ route('announcements.index') }}" class="px-5 py-2.5 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-medium">Batal</a>
                    <button class="px-6 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black"><i class="fa-solid fa-paper-plane mr-1.5"></i> Publish</button>
                </div>
            </form>
        </div>
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
            <div class="font-medium text-[13px] text-amber-900 flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation text-amber-600"></i> Urgent?</div>
            <p class="text-[12.5px] text-amber-800/80 mt-1">Pilih <b>Urgent Deadline</b> agar highlight merah di landing.</p>
        </div>
    </div>
</div>
@endsection
