@extends('layouts.admin')
@section('title', 'Dashboard Mahasiswa')
@section('breadcrumb', 'Dashboard')
@section('content')
<div class="w-full">
    <div class="flex items-center gap-3 mb-6">
        <img src="{{ $student->photo_url ?? 'https://api.dicebear.com/7.x/initials/svg?seed='.urlencode($student->name).'&backgroundColor=11100F' }}" class="w-12 h-12 rounded-full border-2 border-white shadow">
        <div>
            <h1 class="font-semibold text-[18px]">Halo, {{ $student->name }}</h1>
            <p class="font-mono text-[11px] text-[#7A7670]">{{ $student->nim }} • D4 RPL 4B</p>
        </div>
    </div>

    @if(!$student->hosting)
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 text-center">
        <div class="w-12 h-12 rounded-full bg-amber-500 text-white flex items-center justify-center mx-auto mb-3"><i class="fa-solid fa-server text-[18px]"></i></div>
        <h2 class="font-semibold text-[16px]">Belum Punya Hosting</h2>
        <p class="text-[#7A7670] mt-1">Minta admin buatkan hosting buat kamu.</p>
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black"><i class="fa-solid fa-arrow-left text-[11px]"></i> Kembali ke Landing</a>
    </div>
    @else
    <div class="grid lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-[#E8DFD1] rounded-xl p-5">
            <div class="text-[11px] tracking-[0.08em] uppercase font-semibold text-[#7A7670]">Disk Usage</div>
            <div class="text-[24px] font-semibold leading-none mt-2">{{ $student->hosting->diskUsage()['human'] }} <span class="text-[13px] font-normal text-[#7A7670]">/ {{ $student->hosting->quota_mb }} MB</span></div>
            <div class="mt-3 h-2 bg-[#F6F5F1] rounded-full overflow-hidden"><div class="h-full bg-[#11100F] rounded-full" style="width: {{ min(100, ($student->hosting->diskUsage()['bytes']/($student->hosting->quota_mb*1048576))*100) }}%"></div></div>
        </div>
        <div class="bg-white border border-[#E8DFD1] rounded-xl p-5">
            <div class="text-[11px] tracking-[0.08em] uppercase font-semibold text-[#7A7670]">Domain</div>
            <div class="font-mono text-[13px] bg-[#F6F5F1] border border-[#E8DFD1] rounded-lg px-3 py-2 mt-2 truncate">{{ $student->hosting->domain }}</div>
        </div>
        <div class="bg-[#11100F] text-white rounded-xl p-5 flex flex-col justify-between">
            <div class="text-[11px] tracking-[0.08em] uppercase font-semibold text-white/60">URL Hosting</div>
            <a href="{{ $student->hosting->url }}" target="_blank" class="mt-2 inline-flex items-center justify-center gap-2 py-2 rounded-full bg-white text-[#11100F] text-[12px] font-medium hover:bg-white/90"><i class="fa-solid fa-arrow-up-right-from-square text-[11px]"></i> Buka Hosting</a>
            <a href="{{ route('mahasiswa.hosting.files') }}" class="mt-2 inline-flex items-center justify-center gap-2 py-2 rounded-full bg-white/10 text-white text-[12px] font-medium hover:bg-white/20"><i class="fa-solid fa-folder mr-1"></i> File Manager</a>
        </div>
    </div>

    <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-[#E8DFD1] bg-[#FCFBF9] flex items-center justify-between">
            <h3 class="font-semibold text-[14px]">File Manager - {{ $student->hosting->domain }}</h3>
            <a href="{{ route('mahasiswa.hosting.files') }}" class="px-4 py-2 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black"><i class="fa-solid fa-folder mr-1"></i> Buka File Manager</a>
        </div>
    </div>
    @endif
</div>
@endsection