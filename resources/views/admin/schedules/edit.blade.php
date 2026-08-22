@extends('layouts.admin')
@section('title', 'Edit Jadwal')
@section('breadcrumb', 'Jadwal / Edit')
@section('content')
<div class="w-full">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('schedules.index') }}" class="w-9 h-9 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center hover:bg-[#F6F5F1]"><i class="fa-solid fa-arrow-left text-[12px]"></i></a>
        <div class="flex-1"><h1 class="font-semibold text-[18px] leading-none">Edit Jadwal</h1><p class="font-mono text-[11px] text-[#7A7670]">{{ $schedule->hash_id }}</p></div>
        <form action="{{ route('schedules.destroy', $schedule->hash_id) }}" method="POST" onsubmit="return confirmDelete(this, 'Hapus Data?', 'Hapus jadwal ini? Yakin ingin melanjutkan? Tindakan tidak bisa dibatalkan')">@csrf @method('DELETE')<button class="px-4 py-2 rounded-full bg-white border border-red-200 text-red-600 text-[12px] font-medium hover:bg-red-50"><i class="fa-solid fa-trash mr-1"></i> Hapus</button></form>
    </div>
    <div class="grid lg:grid-cols-[1fr_300px] gap-6">
        <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E8DFD1] bg-[#FCFBF9] flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-regular fa-calendar text-[11px]"></i></span>
                <span class="font-medium text-[13px]">Edit Jadwal</span>
            </div>
            <form action="{{ route('schedules.update', $schedule->hash_id) }}" method="POST" class="p-6 space-y-5">
                @csrf @method('PUT')
                @if($errors->any())<div class="rounded-xl bg-red-50 border border-red-200 text-red-700 text-[12px] px-4 py-3"><ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
                <div class="grid md:grid-cols-3 gap-4">
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Hari *</label>
                        <select name="day" required class="form-input">
                            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $d)
                            <option value="{{ $d }}" {{ old('day', $schedule->day)==$d?'selected':'' }}>{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Jam Mulai *</label><input type="time" name="time_start" value="{{ old('time_start', $schedule->time_start) }}" required class="form-input"></div>
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Jam Selesai *</label><input type="time" name="time_end" value="{{ old('time_end', $schedule->time_end) }}" required class="form-input"></div>
                </div>
                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Mata Kuliah *</label><input type="text" name="course" value="{{ old('course', $schedule->course) }}" required class="form-input"></div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Dosen</label><input type="text" name="lecturer" value="{{ old('lecturer', $schedule->lecturer) }}" class="form-input"></div>
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Ruang</label><input type="text" name="room" value="{{ old('room', $schedule->room) }}" class="form-input"></div>
                </div>
                <div class="w-32"><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Urutan</label><input type="number" name="sort_order" value="{{ old('sort_order', $schedule->sort_order) }}" class="form-input"></div>
                <div class="flex justify-end gap-2 pt-2 border-t border-[#E8DFD1]">
                    <a href="{{ route('schedules.index') }}" class="px-5 py-2.5 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-medium hover:bg-[#F6F5F1]">Batal</a>
                    <button class="px-6 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black transition"><i class="fa-solid fa-floppy-disk mr-1.5"></i> Update</button>
                </div>
            </form>
        </div>
        <div class="space-y-4">
            <div class="bg-white border border-[#E8DFD1] rounded-xl p-5">
                <div class="text-[11px] font-semibold tracking-[0.08em] uppercase text-[#7A7670]">Pratinjau</div>
                <div class="mt-3 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center"><i class="fa-regular fa-calendar text-[#7A7670]"></i></div>
                    <div><div class="text-[13px] font-medium">{{ $schedule->day }} • {{ $schedule->time_start }}–{{ $schedule->time_end }}</div><div class="text-[12px] text-[#7A7670]">{{ $schedule->course }}</div></div>
                </div>
            </div>
            <div class="bg-[#11100F] text-white rounded-xl p-5">
                <div class="text-[13px] font-medium flex items-center gap-2"><i class="fa-solid fa-lightbulb text-amber-400"></i> Tips</div>
                <p class="text-[12px] text-white/70 mt-1">Urutan 0 tampil paling atas. Pastikan jam tidak tumpang tindih.</p>
            </div>
        </div>
    </div>
</div>
@endsection
