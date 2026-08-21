@extends('layouts.admin')
@section('title', 'Gallery')
@section('breadcrumb', 'Gallery')
@section('content')
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-5">
    <div>
        <h1 class="font-semibold text-[18px] flex items-center gap-2"><span class="w-8 h-8 rounded-lg bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-images text-[13px]"></i></span> Gallery <span class="font-normal text-[13px] text-[#7A7670]">— {{ $galleries->count() }} foto</span></h1>
        <p class="text-[12.5px] text-[#7A7670] mt-1">Kelola foto kehidupan 4B. Tampil di landing bagian Gallery.</p>
    </div>
    <div class="flex items-center gap-2">
        <div class="hidden sm:flex items-center gap-2 bg-white border border-[#E8DFD1] rounded-full px-3 py-2">
            <i class="fa-solid fa-magnifying-glass text-[11px] text-[#7A7670]"></i>
            <input placeholder="Cari judul..." class="bg-transparent text-[13px] outline-none w-[160px] placeholder:text-[#A8A29E]">
        </div>
        <a href="{{ route('galleries.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black transition"><i class="fa-solid fa-plus text-[11px]"></i> Tambah Foto</a>
    </div>
</div>

<div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden shadow-sm">
    <div class="flex items-center justify-between px-4 py-3 border-b border-[#E8DFD1] bg-[#FCFBF9]">
        <div class="flex items-center gap-2 text-[12px]">
            <span class="font-medium">{{ $galleries->count() }} Foto</span>
            <span class="w-1 h-1 rounded-full bg-[#E8DFD1]"></span>
            <span class="text-[#7A7670]">Urut sort_order</span>
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
                    <th class="px-3 py-3">Preview</th>
                    <th class="px-3 py-3">Judul</th>
                    <th class="px-3 py-3">Caption</th>
                    <th class="px-3 py-3">Urutan</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E8DFD1]/60 text-[13px]">
                @forelse($galleries as $g)
                <tr class="hover:bg-[#FCFBF9] group">
                    <td class="px-4 py-3"><input type="checkbox" class="rounded border-[#E8DFD1]"></td>
                    <td class="px-3 py-3"><img src="{{ $g->image_url }}" alt="{{ $g->title }}" class="w-16 h-10 rounded-lg object-cover border border-[#E8DFD1] bg-[#F6F5F1]"></td>
                    <td class="px-3 py-3 font-medium max-w-[180px] truncate">{{ $g->title }}</td>
                    <td class="px-3 py-3 text-[#7A7670] max-w-[220px] truncate text-[12px]">{{ $g->caption ?? '—' }}</td>
                    <td class="px-3 py-3 font-mono text-[12px]">{{ $g->sort_order }}</td>
                    <td class="px-4 py-3">
                        <div class="flex justify-end gap-1.5">
                            <a href="{{ route('galleries.edit', $g->hash_id) }}" class="px-3 py-1.5 rounded-full bg-white border border-[#E8DFD1] text-[12px] font-medium hover:bg-[#F6F5F1]"><i class="fa-solid fa-pen text-[10px] mr-1"></i> Edit</a>
                            <form action="{{ route('galleries.destroy', $g->hash_id) }}" method="POST" onsubmit="return confirmDelete(this, 'Hapus Data?', 'Hapus foto ini? Yakin ingin melanjutkan? Tindakan tidak bisa dibatalkan')">
                                @csrf @method('DELETE')
                                <button class="w-8 h-8 rounded-full bg-white border border-red-200 text-red-600 flex items-center justify-center hover:bg-red-50"><i class="fa-solid fa-trash text-[11px]"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center">
                    <div class="w-12 h-12 rounded-full bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center mx-auto mb-3"><i class="fa-regular fa-images text-[#7A7670]"></i></div>
                    <div class="text-[13px] font-medium">Belum ada foto</div>
                    <div class="text-[12px] text-[#7A7670]">Tambah foto pertama untuk galeri.</div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="flex items-center justify-between px-4 py-3 border-t border-[#E8DFD1] bg-[#FCFBF9] text-[12px] text-[#7A7670]">
        <span>Menampilkan {{ $galleries->count() }} dari {{ $galleries->count() }}</span>
        <div class="flex items-center gap-1">
            <button class="px-3 py-1.5 rounded-full border border-[#E8DFD1] bg-white disabled:opacity-50" disabled>Sebelumnya</button>
            <span class="px-3 py-1.5 rounded-full bg-[#11100F] text-white">1</span>
            <button class="px-3 py-1.5 rounded-full border border-[#E8DFD1] bg-white">Selanjutnya</button>
        </div>
    </div>
</div>
@endsection
