@extends('layouts.admin')
@section('title', 'Pengaturan Hosting')
@section('breadcrumb', 'Hosting / Pengaturan')
@section('content')
<div class="w-full">
    <div class="bg-white border border-[#E8DFD1] rounded-xl p-6">
        <h2 class="text-[16px] font-semibold mb-1">Pengaturan Domain Hosting</h2>
        <p class="text-[13px] text-[#7A7670] mb-6">Atur domain yang digunakan untuk mengakses portofolio dan hosting kamu.</p>

        <form action="{{ route('mahasiswa.hosting.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-5">
                <label class="block text-[12px] font-semibold text-[#7A7670] uppercase tracking-wider mb-2">Domain</label>
                <div class="relative">
                    <input type="text" name="domain" value="{{ old('domain', $hosting->domain) }}" class="form-input w-full font-mono text-[13px] pr-10" placeholder="nim.d4rpl4b.ryaze.cloud">
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-globe text-[#A8A29E]"></i>
                    </div>
                </div>
                @error('domain')
                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
