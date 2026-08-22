@extends('layouts.admin')
@section('title', 'Hosting per Mahasiswa')
@section('breadcrumb', 'Hosting per Mahasiswa')
@section('content')
<div x-data="{ showCreateModal: false }">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-5">
        <div>
            <h1 class="font-semibold text-[18px] flex items-center gap-2"><span class="w-8 h-8 rounded-lg bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-server text-[13px]"></i></span> Hosting cPanel <span class="font-normal text-[13px] text-[#7A7670]">— {{ $hostings->count() }} hosting aktif</span></h1>
            <p class="text-[12.5px] text-[#7A7670] mt-1">Tiap NIM punya web server sendiri. Full file manager kayak cPanel.</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="hidden sm:flex items-center gap-2 bg-white border border-[#E8DFD1] rounded-full px-3 py-2">
                <i class="fa-solid fa-magnifying-glass text-[11px] text-[#7A7670]"></i>
                <input id="search-hostings" data-page-search placeholder="Cari mahasiswa..." class="bg-transparent text-[13px] outline-none w-[180px] placeholder:text-[#A8A29E]">
            </div>
            <button @click="showCreateModal = true" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black transition"><i class="fa-solid fa-plus text-[11px]"></i> Buat Hosting</button>
        </div>
    </div>

    <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden shadow-sm">
        <div class="flex items-center justify-between px-4 py-3 border-b border-[#E8DFD1] bg-[#FCFBF9]">
            <div class="flex items-center gap-2 text-[12px]">
                <span class="font-medium"><span data-count>{{ $hostings->count() }}</span> Hosting</span>
                <span class="w-1 h-1 rounded-full bg-[#E8DFD1]"></span>
                <span class="text-[#7A7670]">Daftar cPanel</span>
            </div>
            <div class="flex items-center gap-1">
                <button class="w-8 h-8 rounded-lg border border-[#E8DFD1] bg-white flex items-center justify-center text-[#7A7670] hover:text-[#11100F]"><i class="fa-solid fa-arrow-down-wide-short text-[11px]"></i></button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left" data-searchable="search-hostings">
                <thead class="bg-[#F6F5F1] border-b border-[#E8DFD1] text-[11px] font-semibold tracking-[0.08em] uppercase text-[#7A7670]">
                    <tr>
                        <th class="px-4 py-3 w-8"><input type="checkbox" class="rounded border-[#E8DFD1]"></th>
                        <th class="px-3 py-3">Mahasiswa</th>
                        <th class="px-3 py-3">Domain & Status</th>
                        <th class="px-3 py-3">Disk Usage</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E8DFD1]/60 text-[13px]">
                    @forelse($hostings as $h)
                    <tr class="hover:bg-[#FCFBF9] group" data-row>
                        <td class="px-4 py-3"><input type="checkbox" class="rounded border-[#E8DFD1]"></td>
                        <td class="px-3 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $h->student->photo_url ?? 'https://api.dicebear.com/7.x/initials/svg?seed='.urlencode($h->student->name).'&backgroundColor=11100F' }}" class="w-8 h-8 rounded-full border border-[#E8DFD1] object-cover shrink-0">
                                <div class="min-w-0 flex-1">
                                    <div class="font-medium text-[13px] leading-none truncate max-w-[200px]">{{ $h->student->name }}</div>
                                    <div class="font-mono text-[11px] text-[#7A7670] mt-1">{{ $h->student->nim }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-3">
                            <div class="flex flex-col gap-1">
                                <span class="font-mono text-[11.5px] truncate max-w-[180px]">{{ $h->domain }}</span>
                                <div class="flex items-center gap-1.5 text-[11px]">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $h->status==='active' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    <span class="{{ $h->status==='active' ? 'text-emerald-700' : 'text-red-700' }}">{{ $h->status }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-3">
                            <div class="font-mono text-[11.5px] text-[#7A7670]">{{ $h->diskUsage()['human'] }} / {{ $h->quota_mb }} MB</div>
                            <div class="text-[11px] text-[#7A7670] mt-1">{{ $h->diskUsage()['files'] }} files</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ $h->domain_url }}" target="_blank" class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center hover:bg-emerald-100" title="Buka site"><i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i></a>
                                <a href="{{ route('hostings.files', $h->hash_id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white border border-[#E8DFD1] text-[12px] font-medium hover:bg-[#F6F5F1]"><i class="fa-solid fa-folder-open text-[10px]"></i> cPanel</a>
                                <form action="{{ route('hostings.destroy', $h->hash_id) }}" method="POST" onsubmit="return confirmDelete(this, 'Hapus Hosting?', 'Hapus hosting {{ $h->student->name }}? Yakin ingin melanjutkan? Tindakan tidak bisa dibatalkan')">
                                    @csrf @method('DELETE')
                                    <button class="w-8 h-8 rounded-full bg-white border border-red-200 text-red-600 flex items-center justify-center hover:bg-red-50"><i class="fa-solid fa-trash text-[11px]"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr data-empty><td colspan="5" class="px-6 py-12 text-center">
                        <div class="w-12 h-12 rounded-full bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center mx-auto mb-3"><i class="fa-solid fa-server text-[#7A7670]"></i></div>
                        <div class="text-[13px] font-medium">Belum ada hosting aktif</div>
                        <div class="text-[12px] text-[#7A7670]">Klik Buat Hosting untuk membuat akses cPanel.</div>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between px-4 py-3 border-t border-[#E8DFD1] bg-[#FCFBF9] text-[12px] text-[#7A7670]">
            <span>Menampilkan <span data-count>{{ $hostings->count() }}</span> hosting</span>
            <div class="flex items-center gap-1">
                <button class="px-3 py-1.5 rounded-full border border-[#E8DFD1] bg-white disabled:opacity-50" disabled>Sebelumnya</button>
                <span class="px-3 py-1.5 rounded-full bg-[#11100F] text-white">1</span>
                <button class="px-3 py-1.5 rounded-full border border-[#E8DFD1] bg-white disabled:opacity-50" disabled>Selanjutnya</button>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div x-show="showCreateModal" x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak class="fixed inset-0 z-[90] flex items-center justify-center p-4">
        <div @click="showCreateModal=false" class="absolute inset-0 bg-[#141210]/40 backdrop-blur-sm"></div>
        <div x-transition:enter="transition duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white rounded-[16px] border border-[#E8DFD1] shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between px-6 py-4 border-b border-[#E8DFD1] bg-[#FCFBF9]">
                <h3 class="font-semibold text-[16px]">Buat Hosting Baru</h3>
                <button @click="showCreateModal=false" class="w-8 h-8 rounded-full hover:bg-black/5 flex items-center justify-center text-[#7A7670]"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('hostings.store') }}" method="POST" class="flex-1 overflow-y-auto">
                @csrf
                <div class="p-6 space-y-4">
                    @if($errors->any())<div class="rounded-xl bg-red-50 border border-red-200 text-red-700 text-[12px] px-3 py-2">{{ $errors->first() }}</div>@endif
                    <div>
                        <label class="text-[12px] font-semibold text-[#11100F] mb-1.5 block">Mahasiswa <span class="text-red-500">*</span></label>
                        <select name="student_id" required class="form-input w-full bg-[#FDF9F3]">
                            <option value="">Pilih mahasiswa</option>
                            @foreach($students->whereNull('hosting') as $s)
                            <option value="{{ $s->id }}">{{ $s->nim }} — {{ $s->name }}</option>
                            @endforeach
                        </select>
                        @if($students->whereNull('hosting')->isEmpty())<div class="text-[11px] text-emerald-700 mt-1">Semua mahasiswa sudah punya hosting.</div>@endif
                    </div>
                    <div>
                        <label class="text-[12px] font-semibold text-[#11100F] mb-1.5 block">Domain <span class="text-[#7A7670] font-normal">(opsional)</span></label>
                        <input name="domain" placeholder="nim.{{ str_replace('d4rpl4b.', '', parse_url(config('app.url', 'http://d4rpl4b.ryaze.cloud'), PHP_URL_HOST) ?? 'd4rpl4b.ryaze.cloud') }}" class="form-input w-full bg-[#FDF9F3] font-mono text-[13px]">
                        <div class="text-[11px] text-[#7A7670] mt-1">Kosongkan → auto <span class="font-mono">{{ strtolower('nim') }}.{{ str_replace('d4rpl4b.', '', parse_url(config('app.url', 'http://d4rpl4b.ryaze.cloud'), PHP_URL_HOST) ?? 'd4rpl4b.ryaze.cloud') }}</span></div>
                    </div>
                    <div>
                        <label class="text-[12px] font-semibold text-[#11100F] mb-1.5 block">Quota MB</label>
                        <input type="number" name="quota_mb" value="500" min="100" max="5000" class="form-input w-full bg-[#FDF9F3]">
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-[#E8DFD1] bg-[#FCFBF9] flex items-center justify-end gap-2">
                    <button type="button" @click="showCreateModal=false" class="px-5 py-2.5 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-[500] hover:bg-[#F6F5F1]">Batal</button>
                    <button class="px-6 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-[500] hover:bg-black inline-flex items-center gap-1.5"><i class="fa-solid fa-server text-[11px]"></i> Simpan Hosting</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('hostingsData', () => ({
            init() {
                this.$data.showCreateModal = true;
            }
        }))
    })
</script>
@endif
@endsection
