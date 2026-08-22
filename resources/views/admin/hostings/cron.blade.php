@extends('layouts.admin')
@section('title', 'Cron Jobs - ' . $hosting->student->name)
@section('breadcrumb', 'Hosting / ' . $hosting->student->nim . ' / Cron Jobs')
@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('hostings.show', $hosting->hash_id) }}" class="w-9 h-9 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center hover:bg-[#F6F5F1]"><i class="fa-solid fa-arrow-left text-[12px]"></i></a>
    <div class="w-10 h-10 rounded-full bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center text-[#11100F]"><i class="fa-solid fa-clock text-[18px]"></i></div>
    <div class="flex-1 min-w-0">
        <h1 class="font-semibold text-[18px] leading-none">Cron Jobs (Task Scheduler)</h1>
        <p class="font-mono text-[11px] text-[#7A7670]">{{ $hosting->domain }}</p>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <form action="{{ route('hostings.cron.store', $hosting->hash_id) }}" method="POST" class="bg-white border border-[#E8DFD1] rounded-xl p-6">
            @csrf
            <h3 class="font-semibold text-[14px] mb-1">Tambah Cron Job</h3>
            <p class="text-[12px] text-[#7A7670] mb-5">Sistem akan melakukan ping (HTTP GET) ke URL yang kamu tentukan sesuai jadwal.</p>
            
            <div class="mb-4">
                <label class="block text-[11px] uppercase tracking-wider text-[#7A7670] font-semibold mb-1.5">URL Target (Ping URL)</label>
                <input type="url" name="url" placeholder="https://{{ $hosting->domain }}/cron.php" value="{{ old('url') }}" class="w-full bg-[#FCFBF9] border border-[#E8DFD1] text-[#11100F] text-[13px] rounded-lg px-3 py-2.5 outline-none focus:border-[#11100F] focus:ring-1 focus:ring-[#11100F] transition" required>
                @error('url') <span class="text-red-500 text-[11px] mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="mb-5">
                <label class="block text-[11px] uppercase tracking-wider text-[#7A7670] font-semibold mb-1.5">Interval Waktu</label>
                <select name="interval" class="w-full bg-[#FCFBF9] border border-[#E8DFD1] text-[#11100F] text-[13px] rounded-lg px-3 py-2.5 outline-none focus:border-[#11100F] focus:ring-1 focus:ring-[#11100F] transition" required>
                    <option value="everyMinute">Setiap Menit (* * * * *)</option>
                    <option value="everyFiveMinutes">Setiap 5 Menit (*/5 * * * *)</option>
                    <option value="hourly">Setiap Jam (0 * * * *)</option>
                    <option value="daily">Setiap Hari (0 0 * * *)</option>
                </select>
                @error('interval') <span class="text-red-500 text-[11px] mt-1">{{ $message }}</span> @enderror
            </div>
            
            <button type="submit" class="w-full py-2.5 bg-[#11100F] text-white text-[13px] font-medium rounded-lg hover:bg-black transition">Tambahkan Cron Job</button>
        </form>
    </div>
    
    <div class="lg:col-span-2">
        <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
            <div class="p-6 border-b border-[#E8DFD1] bg-[#FCFBF9]">
                <h3 class="font-semibold text-[14px]">Daftar Cron Job Aktif</h3>
            </div>
            @if($cronJobs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-[13px]">
                        <thead>
                            <tr class="bg-[#F6F5F1] text-[#7A7670] font-semibold text-[11px] uppercase tracking-wider">
                                <th class="px-5 py-3 border-b border-[#E8DFD1]">URL Target</th>
                                <th class="px-5 py-3 border-b border-[#E8DFD1]">Interval</th>
                                <th class="px-5 py-3 border-b border-[#E8DFD1]">Status</th>
                                <th class="px-5 py-3 border-b border-[#E8DFD1]">Terakhir Jalan</th>
                                <th class="px-5 py-3 border-b border-[#E8DFD1] text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cronJobs as $job)
                            <tr class="border-b border-[#E8DFD1] last:border-0 hover:bg-[#FCFBF9]">
                                <td class="px-5 py-3"><div class="max-w-[200px] truncate font-mono text-[12px] bg-[#F6F5F1] px-2 py-0.5 rounded" title="{{ $job->url }}">{{ $job->url }}</div></td>
                                <td class="px-5 py-3 font-medium text-[#11100F]">
                                    {{ $job->interval === 'everyMinute' ? '1 Menit' : ($job->interval === 'everyFiveMinutes' ? '5 Menit' : ($job->interval === 'hourly' ? '1 Jam' : '1 Hari')) }}
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-[#7A7670] text-[12px]">{{ $job->last_run ? $job->last_run->diffForHumans() : 'Belum pernah' }}</td>
                                <td class="px-5 py-3 text-right">
                                    <form action="{{ route('hostings.cron.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Hapus cron job ini?')">
                                        @csrf @method('DELETE')
                                        <button class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition"><i class="fa-solid fa-trash text-[12px]"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center flex flex-col items-center justify-center">
                    <div class="w-12 h-12 rounded-full bg-[#F6F5F1] flex items-center justify-center text-[#7A7670] mb-3"><i class="fa-solid fa-clock text-[20px]"></i></div>
                    <div class="font-medium text-[#11100F] text-[14px]">Tidak ada cron job</div>
                    <div class="text-[12px] text-[#7A7670] mt-1">Tambahkan cron job baru pada form di samping.</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
