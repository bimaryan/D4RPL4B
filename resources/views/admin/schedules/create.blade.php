@extends('layouts.admin')
@section('title', 'Tambah Jadwal')
@section('breadcrumb', 'Jadwal / Tambah')
@section('content')
<div class="w-full">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('schedules.index') }}" class="w-9 h-9 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center hover:bg-[#F6F5F1]"><i class="fa-solid fa-arrow-left text-[12px]"></i></a>
        <div><h1 class="font-semibold text-[18px] leading-none">Tambah Jadwal</h1><p class="text-[12.5px] text-[#7A7670]">Isi jadwal kuliah — urutan kecil tampil atas.</p></div>
    </div>
    <div class="grid lg:grid-cols-[1fr_300px] gap-6">
        <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E8DFD1] bg-[#FCFBF9] flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-[#11100F] text-white flex items-center justify-center"><i class="fa-regular fa-calendar text-[11px]"></i></span>
                <span class="font-medium text-[13px]">Form Jadwal</span>
                <span class="ml-auto font-mono text-[11px] bg-amber-50 border border-amber-200 text-amber-700 rounded-full px-2 py-1">Wajib *</span>
            </div>
            <form action="{{ route('schedules.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                @if($errors->any())<div class="rounded-xl bg-red-50 border border-red-200 text-red-700 text-[12px] px-4 py-3"><ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
                <div class="grid md:grid-cols-3 gap-4">
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Hari *</label>
                        <select name="day" required class="form-input">
                            <option value="">Pilih hari</option>
                            <option value="Senin" {{ old('day')=='Senin'?'selected':'' }}>Senin</option>
                            <option value="Selasa" {{ old('day')=='Selasa'?'selected':'' }}>Selasa</option>
                            <option value="Rabu" {{ old('day')=='Rabu'?'selected':'' }}>Rabu</option>
                            <option value="Kamis" {{ old('day')=='Kamis'?'selected':'' }}>Kamis</option>
                            <option value="Jumat" {{ old('day')=='Jumat'?'selected':'' }}>Jumat</option>
                            <option value="Sabtu" {{ old('day')=='Sabtu'?'selected':'' }}>Sabtu</option>
                        </select>
                    </div>
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Jam Mulai *</label><input type="time" name="time_start" value="{{ old('time_start') }}" required class="form-input"></div>
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Jam Selesai *</label><input type="time" name="time_end" value="{{ old('time_end') }}" required class="form-input"></div>
                </div>
                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Mata Kuliah *</label><input type="text" name="course" value="{{ old('course') }}" required placeholder="Contoh: Web Development II" class="form-input"></div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Dosen</label><input type="text" name="lecturer" value="{{ old('lecturer') }}" placeholder="Nama dosen" class="form-input"></div>
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Ruang</label><input type="text" name="room" value="{{ old('room') }}" placeholder="Lab. Software 1" class="form-input"></div>
                </div>
                <div class="w-32"><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Urutan</label><input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-input"></div>
                <div class="flex justify-end gap-2 pt-2 border-t border-[#E8DFD1]">
                    <a href="{{ route('schedules.index') }}" class="px-5 py-2.5 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-medium hover:bg-[#F6F5F1]">Batal</a>
                    <button class="px-6 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black transition"><i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan</button>
                </div>
            </form>
        </div>
        <div class="space-y-4">
            <div class="bg-[#11100F] text-white rounded-xl p-5">
                <div class="font-medium text-[13px] flex items-center gap-2"><i class="fa-solid fa-lightbulb text-amber-400"></i> Tips Jadwal</div>
                <ul class="text-[12.5px] leading-relaxed text-white/70 mt-2 space-y-1.5 list-disc list-inside">
                    <li>Urutan 0 tampil paling atas.</li>
                    <li>Hari bebas, tapi konsisten: Senin–Sabtu.</li>
                    <li>Jam pakai format 08:00 – 10:30.</li>
                </ul>
            </div>
            <div class="bg-white border border-[#E8DFD1] rounded-xl p-5">
                <div class="text-[11px] font-semibold tracking-[0.08em] uppercase text-[#7A7670]">Pratinjau</div>
                <div class="mt-3 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center"><i class="fa-regular fa-calendar text-[#7A7670]"></i></div>
                    <div><div class="text-[13px] font-medium">Senin • 08:00–10:30</div><div class="text-[12px] text-[#7A7670]">Web Development II</div></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
