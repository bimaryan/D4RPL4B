@extends('layouts.admin')
@section('title', 'Pengumuman')
@section('breadcrumb', 'Pengumuman')
@section('content')
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-5">
    <div>
        <h1 class="font-semibold text-[18px] flex items-center gap-2"><span class="w-8 h-8 rounded-lg bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-bullhorn text-[13px]"></i></span> Pengumuman <span class="font-normal text-[13px] text-[#7A7670]">— {{ $announcements->count() }} data</span></h1>
        <p class="text-[12.5px] text-[#7A7670] mt-1">Broadcast ke papan akademik landing. Urgent akan highlight merah.</p>
    </div>
    <div class="flex items-center gap-2">
        <div class="hidden sm:flex items-center gap-2 bg-white border border-[#E8DFD1] rounded-full px-3 py-2">
            <i class="fa-solid fa-magnifying-glass text-[11px] text-[#7A7670]"></i>
            <input id="search-announcements" data-page-search placeholder="Cari judul..." class="bg-transparent text-[13px] outline-none w-[160px] placeholder:text-[#A8A29E]">
        </div>
        <a href="{{ route('announcements.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black transition"><i class="fa-solid fa-plus text-[11px]"></i> Buat Pengumuman</a>
    </div>
</div>

<div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-4 py-3 border-b border-[#E8DFD1] bg-[#FCFBF9]">
        <div class="flex items-center gap-2 text-[12px]">
            <span class="font-medium">{{ $announcements->count() }} Pengumuman</span>
            <span class="w-1 h-1 rounded-full bg-[#E8DFD1]"></span>
            <span class="text-[#7A7670]">Urut tanggal</span>
        </div>
        <div class="flex items-center bg-white border border-[#E8DFD1] rounded-full p-1 text-[12px]">
            <button class="px-3 py-1 rounded-full bg-[#11100F] text-white">Semua</button>
            <button class="px-3 py-1 rounded-full text-[#7A7670] hover:text-[#11100F]">Urgent</button>
            <button class="px-3 py-1 rounded-full text-[#7A7670] hover:text-[#11100F]">General</button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left" data-searchable="search-announcements">
            <thead class="bg-[#F6F5F1] border-b border-[#E8DFD1] text-[11px] font-semibold tracking-[0.08em] uppercase text-[#7A7670]">
                <tr>
                    <th class="px-4 py-3 w-8"><input type="checkbox" class="rounded border-[#E8DFD1]"></th>
                    <th class="px-3 py-3">Judul</th>
                    <th class="px-3 py-3">Kategori</th>
                    <th class="px-3 py-3"><i class="fa-regular fa-calendar mr-1"></i> Tanggal</th>
                    <th class="px-3 py-3">Hash</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E8DFD1]/60 text-[13px]">
                @forelse($announcements as $announcement)
                <tr class="hover:bg-[#FCFBF9] group" data-row>
                    <td class="px-4 py-3"><input type="checkbox" class="rounded border-[#E8DFD1]"></td>
                    <td class="px-3 py-3">
                        <div class="font-medium truncate max-w-[320px]">{{ $announcement->title }}</div>
                        <div class="text-[11px] text-[#7A7670] truncate max-w-[320px]">{{ \Illuminate\Support\Str::limit($announcement->content, 60) }}</div>
                    </td>
                    <td class="px-3 py-3">
                        @if($announcement->category == 'Urgent Deadline')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 border border-red-200 text-red-700 text-[11px] font-semibold"><span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> {{ $announcement->category }}</span>
                        @else
                            <span class="inline-flex px-2.5 py-1 rounded-full bg-[#F6F5F1] border border-[#E8DFD1] text-[#7A7670] text-[11px] font-medium"><i class="fa-solid fa-tag mr-1 text-[10px]"></i> {{ $announcement->category }}</span>
                        @endif
                    </td>
                    <td class="px-3 py-3 font-mono text-[12px]">{{ $announcement->event_date ? \Carbon\Carbon::parse($announcement->event_date)->format('d M Y') : '—' }}</td>
                    <td class="px-3 py-3 font-mono text-[11px] text-[#7A7670]">{{ substr($announcement->hash_id,0,8) }}…</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('announcements.edit', $announcement->hash_id) }}" class="px-3 py-1.5 rounded-full bg-white border border-[#E8DFD1] text-[12px] font-medium hover:bg-[#F6F5F1]"><i class="fa-solid fa-pen text-[10px] mr-1"></i> Edit</a>
                            <form action="{{ route('announcements.destroy', $announcement->hash_id) }}" method="POST" onsubmit="return confirmDelete(this, 'Hapus Data?', 'Hapus pengumuman? Yakin ingin melanjutkan? Tindakan tidak bisa dibatalkan')">
                                @csrf @method('DELETE')
                                <button class="w-8 h-8 rounded-full bg-white border border-red-200 text-red-600 flex items-center justify-center hover:bg-red-50"><i class="fa-solid fa-trash text-[11px]"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr data-empty><td colspan="6" class="px-6 py-12 text-center">
                    <div class="w-12 h-12 rounded-full bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center mx-auto mb-3"><i class="fa-solid fa-bullhorn text-[#7A7670]"></i></div>
                    <div class="text-[13px] font-medium">Belum ada pengumuman</div>
                    <div class="text-[12px] text-[#7A7670]">Buat broadcast pertama untuk papan akademik.</div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="flex items-center justify-between px-4 py-3 border-t border-[#E8DFD1] bg-[#FCFBF9] text-[12px] text-[#7A7670]">
        <span>Menampilkan <span data-count>{{ $announcements->count() }}</span> dari <span data-total>{{ $announcements->count() }}</span></span>
        <div class="flex items-center gap-1">
            <button class="px-3 py-1.5 rounded-full border border-[#E8DFD1] bg-white disabled:opacity-50" disabled>Sebelumnya</button>
            <span class="px-3 py-1.5 rounded-full bg-[#11100F] text-white">1</span>
            <button class="px-3 py-1.5 rounded-full border border-[#E8DFD1] bg-white">Selanjutnya</button>
        </div>
    </div>
</div>
@endsection
