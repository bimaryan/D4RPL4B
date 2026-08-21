@extends('layouts.admin')
@section('title', 'cPanel - ' . $hosting->student->name)
@section('breadcrumb', 'Hosting / ' . $hosting->student->nim)
@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('hostings.index') }}" class="w-9 h-9 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center hover:bg-[#F6F5F1]"><i class="fa-solid fa-arrow-left text-[12px]"></i></a>
    <img src="{{ $hosting->student->photo_url ?? 'https://api.dicebear.com/7.x/initials/svg?seed='.urlencode($hosting->student->name).'&backgroundColor=11100F' }}" class="w-10 h-10 rounded-full border-2 border-white shadow">
    <div class="flex-1 min-w-0">
        <h1 class="font-semibold text-[18px] leading-none">cPanel — {{ $hosting->student->name }}</h1>
        <p class="font-mono text-[11px] text-[#7A7670]">{{ $hosting->student->nim }} • {{ $hosting->domain }} • <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full {{ $hosting->status==='active'?'bg-emerald-500':'bg-red-500' }}"></span> {{ $hosting->status }}</span></p>
    </div>
    <a href="{{ $hosting->url }}" target="_blank" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500 text-white text-[13px] font-medium hover:bg-emerald-600"><i class="fa-solid fa-arrow-up-right-from-square text-[11px]"></i> Buka Site</a>
    <a href="{{ route('hostings.files', $hosting->hash_id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black"><i class="fa-solid fa-folder-open text-[11px]"></i> File Manager</a>
</div>

<div class="grid lg:grid-cols-3 gap-4 mb-6">
    <div class="bg-white border border-[#E8DFD1] rounded-xl p-5">
        <div class="text-[11px] tracking-[0.08em] uppercase font-semibold text-[#7A7670]">Disk Usage</div>
        <div class="text-[24px] font-semibold leading-none mt-2">{{ $usage['human'] }} <span class="text-[13px] font-normal text-[#7A7670]">/ {{ $hosting->quota_mb }} MB</span></div>
        <div class="mt-3 h-2 bg-[#F6F5F1] rounded-full overflow-hidden"><div class="h-full bg-[#11100F] rounded-full" style="width: {{ min(100, ($usage['bytes']/($hosting->quota_mb*1048576))*100) }}%"></div></div>
        <div class="text-[11px] text-[#7A7670] mt-2">{{ $usage['files'] }} files</div>
    </div>
    <div class="bg-white border border-[#E8DFD1] rounded-xl p-5">
        <div class="text-[11px] tracking-[0.08em] uppercase font-semibold text-[#7A7670]">Domain</div>
        <div class="font-mono text-[13px] bg-[#F6F5F1] border border-[#E8DFD1] rounded-lg px-3 py-2 mt-2 truncate">{{ $hosting->domain }}</div>
        <div class="text-[11px] text-[#7A7670] mt-2">Path: <span class="font-mono">{{ $hosting->path }}</span></div>
    </div>
    <div class="bg-[#11100F] text-white rounded-xl p-5 flex flex-col justify-between">
        <div class="text-[11px] tracking-[0.08em] uppercase font-semibold text-white/60">Aksi Cepat</div>
        <div class="grid grid-cols-2 gap-2 mt-3">
            <a href="{{ route('hostings.files', $hosting->hash_id) }}" class="py-2.5 rounded-full bg-white text-[#11100F] text-[13px] font-medium text-center hover:bg-white/90"><i class="fa-solid fa-folder mr-1"></i> File Manager</a>
            <form action="{{ route('hostings.toggle', $hosting->hash_id) }}" method="POST">@csrf<button class="w-full py-2.5 rounded-full border border-white/20 text-white text-[13px] font-medium hover:bg-white/10">{{ $hosting->status==='active' ? 'Suspend' : 'Aktifkan' }}</button></form>
        </div>
        <form action="{{ route('hostings.destroy', $hosting->hash_id) }}" method="POST" onsubmit="return confirmDelete(this, 'Hapus Hosting?', 'Hapus hosting {{ $hosting->student->name }}? Semua file akan hilang permanen.')" class="mt-2">@csrf @method('DELETE')<button class="w-full py-2 rounded-full bg-red-600 text-white text-[12px] font-medium hover:bg-red-700"><i class="fa-solid fa-trash mr-1"></i> Hapus Hosting</button></form>
    </div>
</div>

<!-- cPanel grid -->
<div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-[#E8DFD1] bg-[#FCFBF9]">
        <h3 class="font-semibold text-[14px]">cPanel — Tools</h3>
        <p class="text-[12px] text-[#7A7670]">Full kompleks per mahasiswa, isolasi penuh</p>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 p-6">
        <a href="{{ route('hostings.files', $hosting->hash_id) }}" class="group border border-[#E8DFD1] rounded-xl p-5 hover:border-[#11100F] hover:shadow-[0_8px_24px_rgba(0,0,0,0.06)] transition text-center">
            <div class="w-12 h-12 rounded-xl bg-[#E6F0FF] border border-[#BFDBFE] flex items-center justify-center mx-auto text-[#2563EB] group-hover:scale-105 transition"><i class="fa-solid fa-folder-open text-[18px]"></i></div>
            <div class="font-medium text-[13px] mt-3">File Manager</div>
            <div class="text-[11px] text-[#7A7670]">Kelola file portfolio</div>
        </a>
        <div class="border border-dashed border-[#E8DFD1] rounded-xl p-5 text-center opacity-60">
            <div class="w-12 h-12 rounded-xl bg-[#FFF0E6] border border-[#F2C0AA] flex items-center justify-center mx-auto text-[#E84E0F]"><i class="fa-solid fa-database text-[18px]"></i></div>
            <div class="font-medium text-[13px] mt-3">Database</div>
            <div class="text-[11px] text-[#7A7670]">MySQL per hosting</div>
            <span class="inline-flex mt-2 text-[10px] font-mono bg-amber-50 border border-amber-200 text-amber-700 rounded-full px-2 py-0.5">Coming soon</span>
        </div>
        <div class="border border-dashed border-[#E8DFD1] rounded-xl p-5 text-center opacity-60">
            <div class="w-12 h-12 rounded-xl bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center mx-auto text-[#7A7670]"><i class="fa-solid fa-envelope text-[18px]"></i></div>
            <div class="font-medium text-[13px] mt-3">Email</div>
            <div class="text-[11px] text-[#7A7670]">{{ $hosting->student->nim }}@d4rpl4b.test</div>
        </div>
        <div class="border border-dashed border-[#E8DFD1] rounded-xl p-5 text-center opacity-60">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center mx-auto text-emerald-600"><i class="fa-solid fa-shield-halved text-[18px]"></i></div>
            <div class="font-medium text-[13px] mt-3">SSL</div>
            <div class="text-[11px] text-emerald-700">Auto Let's Encrypt</div>
        </div>
        <div class="border border-dashed border-[#E8DFD1] rounded-xl p-5 text-center opacity-60">
            <div class="w-12 h-12 rounded-xl bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center mx-auto"><i class="fa-solid fa-terminal text-[18px]"></i></div>
            <div class="font-medium text-[13px] mt-3">Terminal</div>
            <div class="text-[11px] text-[#7A7670]">Web SSH</div>
        </div>
        <div class="border border-dashed border-[#E8DFD1] rounded-xl p-5 text-center opacity-60">
            <div class="w-12 h-12 rounded-xl bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center mx-auto"><i class="fa-solid fa-chart-line text-[18px]"></i></div>
            <div class="font-medium text-[13px] mt-3">Metrics</div>
            <div class="text-[11px] text-[#7A7670]">{{ $usage['human'] }} used</div>
        </div>
        <div class="border border-dashed border-[#E8DFD1] rounded-xl p-5 text-center opacity-60">
            <div class="w-12 h-12 rounded-xl bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center mx-auto"><i class="fa-solid fa-clock text-[18px]"></i></div>
            <div class="font-medium text-[13px] mt-3">Cron Jobs</div>
            <div class="text-[11px] text-[#7A7670]">Scheduler</div>
        </div>
        <div class="border border-dashed border-[#E8DFD1] rounded-xl p-5 text-center opacity-60">
            <div class="w-12 h-12 rounded-xl bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center mx-auto"><i class="fa-solid fa-box-open text-[18px]"></i></div>
            <div class="font-medium text-[13px] mt-3">Backup</div>
            <div class="text-[11px] text-[#7A7670]">Daily backup</div>
        </div>
    </div>
</div>
@endsection
