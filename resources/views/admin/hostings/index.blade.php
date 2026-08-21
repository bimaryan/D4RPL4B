@extends('layouts.admin')
@section('title', 'Hosting')
@section('breadcrumb', 'Hosting per Mahasiswa')
@section('content')
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-semibold text-[18px] flex items-center gap-2"><span class="w-8 h-8 rounded-lg bg-[#11100F] text-white flex items-center justify-center"><i class="fa-solid fa-server text-[13px]"></i></span> Hosting cPanel <span class="font-normal text-[13px] text-[#7A7670]">— per Mahasiswa isolasi</span></h1>
        <p class="text-[12.5px] text-[#7A7670] mt-1">Tiap NIM punya web server sendiri di <span class="font-mono bg-white border border-[#E8DFD1] rounded px-1.5 py-0.5">/hosting/{hash}</span> — full file manager kayak cPanel.</p>
    </div>
    <div class="flex items-center gap-2">
        <span class="hidden sm:inline-flex items-center gap-2 bg-white border border-[#E8DFD1] rounded-full px-3 py-2 text-[12px] font-mono text-[#7A7670]"><span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> {{ $hostings->count() }} hosting aktif</span>
    </div>
</div>

<div class="grid lg:grid-cols-[1fr_380px] gap-6">
    <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-[#E8DFD1] bg-[#FCFBF9] flex items-center justify-between">
            <span class="font-medium text-[13px] flex items-center gap-2"><i class="fa-solid fa-hard-drive text-[#7A7670]"></i> Daftar Hosting</span>
            <span class="text-[11px] font-mono bg-[#11100F] text-white rounded-full px-2 py-1">{{ $hostings->count() }} / {{ $students->count() }} mahasiswa</span>
        </div>
        <div class="divide-y divide-[#E8DFD1]/60">
            @forelse($hostings as $h)
            <div class="flex items-center gap-4 p-4 hover:bg-[#FCFBF9]">
                <img src="{{ $h->student->photo_url ?? 'https://api.dicebear.com/7.x/initials/svg?seed='.urlencode($h->student->name).'&backgroundColor=11100F' }}" class="w-10 h-10 rounded-full border border-[#E8DFD1] object-cover">
                <div class="min-w-0 flex-1">
                    <div class="font-medium text-[13px] leading-none truncate">{{ $h->student->name }} <span class="font-mono text-[11px] text-[#7A7670]">{{ $h->student->nim }}</span></div>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="font-mono text-[11px] bg-[#F6F5F1] border border-[#E8DFD1] rounded-full px-2 py-0.5 truncate max-w-[180px]">{{ $h->domain }}</span>
                        <span class="w-2 h-2 rounded-full {{ $h->status==='active' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                        <span class="text-[11px] {{ $h->status==='active' ? 'text-emerald-700' : 'text-red-700' }}">{{ $h->status }}</span>
                    </div>
                </div>
                <div class="hidden sm:block text-right">
                    <div class="font-mono text-[11px] text-[#7A7670]">{{ $h->diskUsage()['human'] }} / {{ $h->quota_mb }} MB</div>
                    <div class="text-[11px] text-[#7A7670]">{{ $h->diskUsage()['files'] }} files</div>
                </div>
                <div class="flex items-center gap-1.5">
                    <a href="{{ $h->url }}" target="_blank" class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center hover:bg-emerald-600" title="Buka site"><i class="fa-solid fa-arrow-up-right-from-square text-[11px]"></i></a>
                    <a href="{{ route('hostings.files', $h->hash_id) }}" class="px-3 py-1.5 rounded-full bg-[#11100F] text-white text-[12px] font-medium hover:bg-black"><i class="fa-solid fa-folder-open mr-1"></i> cPanel</a>
                </div>
            </div>
            @empty
            <div class="py-12 text-center">
                <div class="w-12 h-12 rounded-full bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center mx-auto mb-3"><i class="fa-solid fa-server text-[#7A7670]"></i></div>
                <div class="text-[13px] font-medium">Belum ada hosting</div>
                <div class="text-[12px] text-[#7A7670]">Buat hosting untuk mahasiswa di kanan.</div>
            </div>
            @endforelse
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
            <div class="px-4 py-3 border-b border-[#E8DFD1] bg-[#FCFBF9] font-medium text-[13px] flex items-center gap-2"><i class="fa-solid fa-plus text-[#7A7670]"></i> Buat Hosting Baru</div>
            <form action="{{ route('hostings.store') }}" method="POST" class="p-4 space-y-4">
                @csrf
                @if($errors->any())<div class="rounded-xl bg-red-50 border border-red-200 text-red-700 text-[12px] px-3 py-2">{{ $errors->first() }}</div>@endif
                <div>
                    <label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Mahasiswa *</label>
                    <select name="student_id" required class="form-input">
                        <option value="">Pilih mahasiswa</option>
                        @foreach($students->whereNull('hosting') as $s)
                        <option value="{{ $s->id }}">{{ $s->nim }} — {{ $s->name }}</option>
                        @endforeach
                    </select>
                    @if($students->whereNull('hosting')->isEmpty())<div class="text-[11px] text-emerald-700 mt-1">Semua mahasiswa sudah punya hosting.</div>@endif
                </div>
                <div>
                    <label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Domain (opsional)</label>
                    <input name="domain" placeholder="nim.d4rpl4b.test" class="form-input font-mono text-[12px]">
                    <div class="text-[11px] text-[#7A7670] mt-1">Kosongkan → auto <span class="font-mono">{{ strtolower('nim') }}.d4rpl4b.test</span></div>
                </div>
                <div>
                    <label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Quota MB</label>
                    <input type="number" name="quota_mb" value="500" min="100" max="5000" class="form-input w-28">
                </div>
                <button class="w-full py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black"><i class="fa-solid fa-server mr-1.5"></i> Buat Hosting</button>
            </form>
        </div>

        <div class="bg-[#11100F] text-white rounded-xl p-5">
            <div class="font-medium text-[13px] flex items-center gap-2"><i class="fa-solid fa-circle-info text-[#7DD3FC]"></i> Cara kerja</div>
            <ul class="text-[12px] leading-relaxed text-white/70 mt-2 space-y-1.5 list-disc list-inside">
                <li>Tiap hosting isolasi di <span class="font-mono text-white">hostings/{hash}</span></li>
                <li>Upload ZIP portfolio atau kelola via File Manager</li>
                <li>Akses via <span class="font-mono">/hosting/{hash}</span> atau domain custom</li>
                <li>Auto WebP untuk gambar, full web server untuk hosting</li>
            </ul>
        </div>
    </div>
</div>
@endsection
