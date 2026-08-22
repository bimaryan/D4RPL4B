@extends('layouts.admin')
@section('title', 'Database MySQL - ' . $hosting->student->name)
@section('breadcrumb', 'Hosting / ' . $hosting->student->nim . ' / Database')
@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('hostings.show', $hosting->hash_id) }}" class="w-9 h-9 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center hover:bg-[#F6F5F1]"><i class="fa-solid fa-arrow-left text-[12px]"></i></a>
    <div class="w-10 h-10 rounded-full bg-[#FFF0E6] border border-[#F2C0AA] flex items-center justify-center text-[#E84E0F]"><i class="fa-solid fa-database text-[18px]"></i></div>
    <div class="flex-1 min-w-0">
        <h1 class="font-semibold text-[18px] leading-none">Database MySQL</h1>
        <p class="font-mono text-[11px] text-[#7A7670]">{{ $hosting->student->name }} ({{ $hosting->student->nim }})</p>
    </div>
</div>

<div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden mb-6">
    <div class="p-6 border-b border-[#E8DFD1] bg-[#FCFBF9] flex justify-between items-center">
        <div>
            <h3 class="font-semibold text-[14px]">Daftar Database</h3>
            <p class="text-[12px] text-[#7A7670]">Setiap mahasiswa dibatasi 1 database MySQL.</p>
        </div>
        @if($databases->count() === 0)
        <form action="{{ route('hostings.database.create', $hosting->hash_id) }}" method="POST">
            @csrf
            <button class="px-4 py-2 bg-[#11100F] text-white text-[13px] font-medium rounded-full hover:bg-black"><i class="fa-solid fa-plus mr-1"></i> Buat Database</button>
        </form>
        @endif
    </div>
    
    @if($databases->count() > 0)
    <div class="p-6">
        @foreach($databases as $db)
        <div class="border border-[#E8DFD1] rounded-xl p-5 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 h-full bg-[#E84E0F]"></div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <div class="text-[11px] uppercase tracking-wider text-[#7A7670] font-semibold mb-1">Database Name</div>
                    <div class="font-mono text-[14px] bg-[#F6F5F1] px-3 py-1.5 rounded border border-[#E8DFD1] inline-block">{{ $db->db_name }}</div>
                </div>
                <div>
                    <div class="text-[11px] uppercase tracking-wider text-[#7A7670] font-semibold mb-1">Status</div>
                    <div class="inline-flex items-center gap-1.5 text-[13px] font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Active
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-[11px] uppercase tracking-wider text-[#7A7670] font-semibold mb-1">MySQL Username</div>
                    <div class="font-mono text-[14px] bg-[#F6F5F1] px-3 py-1.5 rounded border border-[#E8DFD1] inline-block">{{ $db->db_user }}</div>
                </div>
                <div class="mt-4">
                    <div class="text-[11px] uppercase tracking-wider text-[#7A7670] font-semibold mb-1">MySQL Password</div>
                    <div class="font-mono text-[14px] bg-[#F6F5F1] px-3 py-1.5 rounded border border-[#E8DFD1] inline-block">{{ $db->db_password }}</div>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t border-[#E8DFD1]">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-[12px] text-[#7A7670]"><i class="fa-solid fa-circle-info mr-1"></i> Gunakan kredensial ini di file <code>.env</code> aplikasi Laravel/PHP mahasiswa:</div>
                    @if(env('PMA_URL'))
                    <a href="{{ env('PMA_URL') }}" target="_blank" class="px-3 py-1.5 bg-[#11100F] text-white text-[11px] font-medium rounded-full hover:bg-black transition"><i class="fa-solid fa-server mr-1"></i> Buka phpMyAdmin</a>
                    @endif
                </div>
                <div class="bg-[#11100F] text-[#A3A3A3] p-4 rounded-lg font-mono text-[12px] leading-relaxed">
                    DB_CONNECTION=mysql<br>
                    DB_HOST={{ env('PANEL_MYSQL_HOST', '127.0.0.1') }}<br>
                    DB_PORT=3306<br>
                    <span class="text-white">DB_DATABASE={{ $db->db_name }}</span><br>
                    <span class="text-white">DB_USERNAME={{ $db->db_user }}</span><br>
                    <span class="text-white">DB_PASSWORD={{ $db->db_password }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="p-12 text-center flex flex-col items-center justify-center">
        <div class="w-16 h-16 rounded-full bg-[#F6F5F1] flex items-center justify-center text-[#7A7670] mb-4"><i class="fa-solid fa-database text-[24px]"></i></div>
        <div class="font-medium text-[#11100F] text-[15px]">Belum Ada Database</div>
        <div class="text-[13px] text-[#7A7670] mt-1 max-w-sm">Klik tombol "Buat Database" di atas untuk secara otomatis men-generate database dan user MySQL untuk mahasiswa ini.</div>
    </div>
    @endif
</div>
@endsection
