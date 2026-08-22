@extends('layouts.admin')
@section('title', 'SSL Certificate - ' . $hosting->student->name)
@section('breadcrumb', 'Hosting / ' . $hosting->student->nim . ' / SSL')
@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('hostings.show', $hosting->hash_id) }}" class="w-9 h-9 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center hover:bg-[#F6F5F1]"><i class="fa-solid fa-arrow-left text-[12px]"></i></a>
    <div class="w-10 h-10 rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600"><i class="fa-solid fa-shield-halved text-[18px]"></i></div>
    <div class="flex-1 min-w-0">
        <h1 class="font-semibold text-[18px] leading-none">SSL Certificate</h1>
        <p class="font-mono text-[11px] text-[#7A7670]">{{ $hosting->domain }}</p>
    </div>
</div>

<div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden mb-6">
    <div class="p-8 text-center flex flex-col items-center justify-center border-b border-[#E8DFD1]">
        <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 mb-5 shadow-[0_0_0_10px_rgba(16,185,129,0.1)]"><i class="fa-solid fa-lock text-[32px]"></i></div>
        <div class="font-semibold text-[20px] text-[#11100F]">Koneksi Aman (HTTPS) Aktif</div>
        <div class="text-[14px] text-[#7A7670] mt-2 max-w-md mx-auto">Sertifikat SSL dikelola secara otomatis oleh <b>Cloudflare Universal SSL</b>. Traffic sudah dienkripsi *End-to-End*.</div>
        <a href="https://{{ $hosting->domain }}" target="_blank" class="mt-6 inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-emerald-600 text-white font-medium hover:bg-emerald-700 transition shadow-sm"><i class="fa-solid fa-arrow-up-right-from-square text-[12px]"></i> Kunjungi Situs (HTTPS)</a>
    </div>
    <div class="grid sm:grid-cols-3 gap-6 p-6 bg-[#FCFBF9]">
        <div>
            <div class="text-[11px] uppercase tracking-wider text-[#7A7670] font-semibold mb-1">Provider</div>
            <div class="font-medium text-[14px]">Cloudflare, Inc.</div>
        </div>
        <div>
            <div class="text-[11px] uppercase tracking-wider text-[#7A7670] font-semibold mb-1">Status</div>
            <div class="inline-flex items-center gap-1.5 text-[13px] font-medium text-emerald-600">
                <i class="fa-solid fa-circle-check"></i> Auto-Renew
            </div>
        </div>
        <div>
            <div class="text-[11px] uppercase tracking-wider text-[#7A7670] font-semibold mb-1">Type</div>
            <div class="font-medium text-[14px]">Wildcard SSL (*.ryaze.cloud)</div>
        </div>
    </div>
</div>
@endsection
