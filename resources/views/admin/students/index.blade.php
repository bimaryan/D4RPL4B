@extends('layouts.admin')
@section('title', 'Mahasiswa')
@section('breadcrumb', 'Mahasiswa')
@section('content')
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-5">
    <div>
        <h1 class="font-semibold text-[18px] flex items-center gap-2"><span class="w-8 h-8 rounded-lg bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-users text-[13px]"></i></span> Mahasiswa <span class="font-normal text-[13px] text-[#7A7670]">— {{ $students->count() }} data</span></h1>
        <p class="text-[12.5px] text-[#7A7670] mt-1">Kelola roster kelas. Perubahan langsung tampil di landing.</p>
    </div>
    <div class="flex items-center gap-2">
        <div class="hidden sm:flex items-center gap-2 bg-white border border-[#E8DFD1] rounded-full px-3 py-2">
            <i class="fa-solid fa-magnifying-glass text-[11px] text-[#7A7670]"></i>
            <input placeholder="Cari NIM / nama..." class="bg-transparent text-[13px] outline-none w-[180px] placeholder:text-[#A8A29E]">
        </div>
        <a href="{{ route('students.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black transition"><i class="fa-solid fa-plus text-[11px]"></i> Tambah</a>
    </div>
</div>

<div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden shadow-sm">
    <div class="flex items-center justify-between px-4 py-3 border-b border-[#E8DFD1] bg-[#FCFBF9]">
        <div class="flex items-center gap-2 text-[12px]">
            <span class="font-medium">{{ $students->count() }} Mahasiswa</span>
            <span class="w-1 h-1 rounded-full bg-[#E8DFD1]"></span>
            <span class="text-[#7A7670]">Urut NIM</span>
        </div>
        <div class="flex items-center gap-1">
            <button class="w-8 h-8 rounded-lg border border-[#E8DFD1] bg-white flex items-center justify-center text-[#7A7670] hover:text-[#11100F]"><i class="fa-solid fa-arrow-down-wide-short text-[11px]"></i></button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-[#F6F5F1] border-b border-[#E8DFD1] text-[11px] font-semibold tracking-[0.08em] uppercase text-[#7A7670]">
                <tr>
                    <th class="px-4 py-3 w-8"><input type="checkbox" class="rounded border-[#E8DFD1]"></th>
                    <th class="px-3 py-3">Mahasiswa</th>
                    <th class="px-3 py-3">NIM</th>
                    <th class="px-3 py-3">Tautan</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E8DFD1]/60 text-[13px]">
                @forelse($students as $student)
                <tr class="hover:bg-[#FCFBF9] group">
                    <td class="px-4 py-3"><input type="checkbox" class="rounded border-[#E8DFD1]"></td>
                    <td class="px-3 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $student->photo_url ?? 'https://api.dicebear.com/7.x/initials/svg?seed='.urlencode($student->name).'&backgroundColor=F5EFE6' }}" class="w-8 h-8 rounded-full border border-[#E8DFD1] bg-white shrink-0 object-cover">
                            <div class="min-w-0">
                                <div class="font-medium leading-none truncate max-w-[180px]">{{ $student->name }}</div>
                                <div class="text-[11px] font-mono text-[#7A7670]">ID: {{ substr($student->hash_id,0,6) }}…</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 py-3 font-mono text-[12px]">{{ $student->nim }}</td>
                    <td class="px-3 py-3">
                        <div class="flex items-center gap-1.5">
                            @if($student->github_url) <a href="{{ $student->github_url }}" target="_blank" class="w-7 h-7 rounded-full bg-[#11100F] text-white flex items-center justify-center hover:bg-black"><i class="fa-brands fa-github text-[11px]"></i></a> @else <span class="w-7 h-7 rounded-full border border-dashed border-[#E8DFD1] flex items-center justify-center text-[#A8A29E]"><i class="fa-brands fa-github text-[11px]"></i></span> @endif
                            @if($student->linkedin_url) <a href="{{ $student->linkedin_url }}" target="_blank" class="w-7 h-7 rounded-full bg-[#0A66C2] text-white flex items-center justify-center"><i class="fa-brands fa-linkedin-in text-[11px]"></i></a> @else <span class="w-7 h-7 rounded-full border border-dashed border-[#E8DFD1] flex items-center justify-center text-[#A8A29E]"><i class="fa-brands fa-linkedin-in text-[11px]"></i></span> @endif
                            @if($student->portfolio_url) <a href="{{ $student->portfolio_url }}" target="_blank" class="w-7 h-7 rounded-full border border-[#E8DFD1] bg-white flex items-center justify-center hover:bg-[#F6F5F1]"><i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i></a> @endif
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('students.edit', $student->hash_id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white border border-[#E8DFD1] text-[12px] font-medium hover:bg-[#F6F5F1]"><i class="fa-solid fa-pen text-[10px]"></i> Edit</a>
                            <form action="{{ route('students.destroy', $student->hash_id) }}" method="POST" onsubmit="return confirmDelete(this, 'Hapus Data?', 'Hapus {{ $student->name }}? Yakin ingin melanjutkan? Tindakan tidak bisa dibatalkan')">
                                @csrf @method('DELETE')
                                <button class="w-8 h-8 rounded-full bg-white border border-red-200 text-red-600 flex items-center justify-center hover:bg-red-50"><i class="fa-solid fa-trash text-[11px]"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center">
                    <div class="w-12 h-12 rounded-full bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center mx-auto mb-3"><i class="fa-regular fa-folder-open text-[#7A7670]"></i></div>
                    <div class="text-[13px] font-medium">Belum ada mahasiswa</div>
                    <div class="text-[12px] text-[#7A7670]">Tambah data pertama untuk tampil di landing.</div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between px-4 py-3 border-t border-[#E8DFD1] bg-[#FCFBF9] text-[12px] text-[#7A7670]">
        <span>Menampilkan {{ $students->count() }} dari {{ $students->count() }}</span>
        <div class="flex items-center gap-1">
            <button class="px-3 py-1.5 rounded-full border border-[#E8DFD1] bg-white disabled:opacity-50" disabled>Sebelumnya</button>
            <span class="px-3 py-1.5 rounded-full bg-[#11100F] text-white">1</span>
            <button class="px-3 py-1.5 rounded-full border border-[#E8DFD1] bg-white">Selanjutnya</button>
        </div>
    </div>
</div>
@endsection
