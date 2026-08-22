@extends('layouts.admin')
@section('title', 'Metrics - ' . $hosting->student->name)
@section('breadcrumb', 'Hosting / ' . $hosting->student->nim . ' / Metrics')
@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('hostings.show', $hosting->hash_id) }}" class="w-9 h-9 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center hover:bg-[#F6F5F1]"><i class="fa-solid fa-arrow-left text-[12px]"></i></a>
    <div class="w-10 h-10 rounded-full bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center text-[#11100F]"><i class="fa-solid fa-chart-line text-[18px]"></i></div>
    <div class="flex-1 min-w-0">
        <h1 class="font-semibold text-[18px] leading-none">Metrics & Resource Usage</h1>
        <p class="font-mono text-[11px] text-[#7A7670]">{{ $hosting->domain }}</p>
    </div>
</div>

<div class="grid md:grid-cols-2 gap-6">
    <div class="bg-white border border-[#E8DFD1] rounded-xl p-6 relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full bg-[#F6F5F1] opacity-50"></div>
        <div class="text-[12px] tracking-[0.08em] uppercase font-semibold text-[#7A7670] mb-6 relative z-10">Disk Usage</div>
        <div class="flex items-end gap-3 relative z-10">
            <div class="text-[40px] font-bold leading-none">{{ $usage['human'] }}</div>
            <div class="text-[14px] text-[#7A7670] mb-2 font-medium">/ {{ $hosting->quota_mb }} MB Limit</div>
        </div>
        @php
            $percentage = min(100, ($usage['bytes'] / ($hosting->quota_mb * 1048576)) * 100);
            $color = $percentage > 90 ? 'bg-red-500' : ($percentage > 70 ? 'bg-amber-500' : 'bg-[#11100F]');
        @endphp
        <div class="mt-8 relative z-10">
            <div class="flex justify-between text-[11px] font-semibold text-[#7A7670] mb-2">
                <span>0%</span>
                <span>{{ round($percentage, 1) }}% Used</span>
                <span>100%</span>
            </div>
            <div class="h-3 bg-[#F6F5F1] rounded-full overflow-hidden shadow-inner">
                <div class="h-full {{ $color }} rounded-full" style="width: {{ $percentage }}%"></div>
            </div>
        </div>
    </div>
    
    <div class="bg-white border border-[#E8DFD1] rounded-xl p-6 relative overflow-hidden flex flex-col justify-between">
        <div>
            <div class="text-[12px] tracking-[0.08em] uppercase font-semibold text-[#7A7670] mb-6">File Statistics</div>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-xl bg-[#F6F5F1] text-[#11100F] flex items-center justify-center"><i class="fa-solid fa-file text-[20px]"></i></div>
                <div>
                    <div class="text-[24px] font-bold leading-none">{{ number_format($usage['files']) }}</div>
                    <div class="text-[12px] text-[#7A7670] mt-1 font-medium">Total Files</div>
                </div>
            </div>
        </div>
        <div class="p-4 bg-[#FCFBF9] border border-[#E8DFD1] rounded-lg flex gap-3 text-[12px] text-[#7A7670]">
            <i class="fa-solid fa-circle-info text-[#11100F] mt-0.5"></i>
            <div>
                Data usage disk mungkin membutuhkan waktu beberapa menit untuk diperbarui secara akurat jika file baru saja diunggah.
            </div>
        </div>
    </div>
</div>
@endsection
