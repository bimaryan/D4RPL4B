@extends('layouts.admin')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')
@section('content')
<div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-6">
    <div>
        <h1 class="font-display text-[26px] font-semibold tracking-tight">Dashboard</h1>
        <p class="text-[13px] text-[#7A7670] mt-1">Ringkasan data landing & aktivitas terbaru — kelola dengan cepat.</p>
    </div>
    <div class="flex items-center gap-2">
        <span class="hidden sm:inline-flex items-center gap-2 text-[12px] font-mono bg-white border border-[#E8DFD1] rounded-full px-3 py-1.5 text-[#7A7670]"><i class="fa-regular fa-calendar"></i> {{ now()->translatedFormat('l, d F Y') }}</span>
        <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black transition"><i class="fa-solid fa-plus text-[11px]"></i> Proyek Baru</a>
    </div>
</div>

<!-- stats 5 -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-6">
    <div class="bg-white border border-[#E8DFD1] rounded-xl p-5">
        <div class="flex items-start justify-between">
            <div class="w-9 h-9 rounded-lg bg-[#FFF0E6] border border-[#F2C0AA] flex items-center justify-center text-[#E84E0F]"><i class="fa-solid fa-users text-[13px]"></i></div>
            <span class="inline-flex items-center gap-1 text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full px-2 py-1"><i class="fa-solid fa-arrow-trend-up text-[10px]"></i> 100%</span>
        </div>
        <div class="mt-4 text-[11px] tracking-[0.08em] uppercase font-semibold text-[#7A7670]">Mahasiswa</div>
        <div class="flex items-baseline gap-2 mt-1">
            <div class="text-[28px] font-semibold leading-none">{{ $studentsCount }}</div>
            <div class="text-[11px] text-[#7A7670]">/ 30</div>
        </div>
        <div class="mt-3 h-1.5 bg-[#F6F5F1] rounded-full overflow-hidden"><div class="h-full bg-[#11100F] rounded-full" style="width: {{ min(100, ($studentsCount/30)*100) }}%"></div></div>
    </div>
    <div class="bg-white border border-[#E8DFD1] rounded-xl p-5">
        <div class="flex items-start justify-between">
            <div class="w-9 h-9 rounded-lg bg-[#11100F] text-white flex items-center justify-center"><i class="fa-solid fa-layer-group text-[13px]"></i></div>
            <a href="{{ route('projects.index') }}" class="text-[11px] font-medium border border-[#E8DFD1] rounded-full px-2 py-1 hover:bg-[#F6F5F1]">Lihat <i class="fa-solid fa-arrow-right ml-1 text-[9px]"></i></a>
        </div>
        <div class="mt-4 text-[11px] tracking-[0.08em] uppercase font-semibold text-[#7A7670]">Karya</div>
        <div class="text-[28px] font-semibold leading-none mt-1">{{ $projectsCount }}</div>
        <div class="text-[12px] text-[#7A7670] mt-1">di showcase</div>
    </div>
    <div class="bg-white border border-[#E8DFD1] rounded-xl p-5">
        <div class="flex items-start justify-between">
            <div class="w-9 h-9 rounded-lg bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600"><i class="fa-solid fa-bullhorn text-[13px]"></i></div>
            <span class="text-[11px] font-mono bg-amber-50 border border-amber-200 text-amber-700 rounded-full px-2 py-1">{{ $announcementsCount }} aktif</span>
        </div>
        <div class="mt-4 text-[11px] tracking-[0.08em] uppercase font-semibold text-[#7A7670]">Pengumuman</div>
        <div class="text-[28px] font-semibold leading-none mt-1">{{ $announcementsCount }}</div>
        <div class="text-[12px] text-[#7A7670] mt-1">papan akademik</div>
    </div>
    <div class="bg-white border border-[#E8DFD1] rounded-xl p-5">
        <div class="flex items-start justify-between">
            <div class="w-9 h-9 rounded-lg bg-[#E6F0FF] border border-[#BFDBFE] flex items-center justify-center text-[#2563EB]"><i class="fa-solid fa-images text-[13px]"></i></div>
            <a href="{{ route('galleries.index') }}" class="text-[11px] font-medium border border-[#E8DFD1] rounded-full px-2 py-1 hover:bg-[#F6F5F1]">Kelola</a>
        </div>
        <div class="mt-4 text-[11px] tracking-[0.08em] uppercase font-semibold text-[#7A7670]">Gallery</div>
        <div class="text-[28px] font-semibold leading-none mt-1">{{ $galleriesCount ?? 0 }}</div>
        <div class="text-[12px] text-[#7A7670] mt-1">foto kehidupan</div>
    </div>
    <div class="bg-[#11100F] text-white rounded-xl p-5 flex flex-col justify-between">
        <div class="flex items-start justify-between">
            <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center"><i class="fa-regular fa-calendar text-[13px]"></i></div>
            <span class="text-[11px] bg-white/10 rounded-full px-2 py-1">{{ $schedulesCount ?? 0 }} jadwal</span>
        </div>
        <div class="mt-4 text-[11px] tracking-[0.08em] uppercase font-semibold text-white/60">Jadwal Kuliah</div>
        <div class="text-[28px] font-semibold leading-none mt-1">{{ $schedulesCount ?? 0 }}</div>
        <a href="{{ route('schedules.index') }}" class="mt-3 inline-flex items-center justify-center gap-2 py-2 rounded-full bg-white text-[#11100F] text-[12px] font-medium hover:bg-white/90"><i class="fa-solid fa-pen text-[11px]"></i> Kelola Jadwal</a>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
    <div class="xl:col-span-2 space-y-4">
        <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-[#E8DFD1]">
                <h3 class="font-semibold text-[14px] flex items-center gap-2"><i class="fa-solid fa-layer-group text-[#7A7670]"></i> Karya Terbaru</h3>
                <a href="{{ route('projects.index') }}" class="text-[12px] font-medium text-[#7A7670] hover:text-[#11100F]">Lihat semua <i class="fa-solid fa-arrow-right ml-1 text-[10px]"></i></a>
            </div>
            <div class="divide-y divide-[#E8DFD1]/70">
                @forelse($recentProjects as $p)
                <div class="flex items-center gap-4 px-5 py-4 hover:bg-[#F6F5F1]/60">
                    <div class="w-10 h-10 rounded-lg bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center shrink-0 overflow-hidden">
                        @if($p->image_src)<img src="{{ $p->image_src }}" class="w-full h-full object-cover">@else<i class="fa-regular fa-image text-[#7A7670]"></i>@endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-[13px] font-medium truncate">{{ $p->title }}</div>
                        <div class="text-[11px] font-mono text-[#7A7670] truncate">{{ is_array($p->tech_stack) ? implode(' • ', array_slice($p->tech_stack,0,3)) : '—' }}</div>
                    </div>
                    <a href="{{ route('projects.edit', $p->hash_id) }}" class="w-8 h-8 rounded-full border border-[#E8DFD1] bg-white flex items-center justify-center hover:bg-[#11100F] hover:text-white transition"><i class="fa-solid fa-pen text-[11px]"></i></a>
                </div>
                @empty
                <div class="py-10 text-center text-[13px] text-[#7A7670]"><i class="fa-regular fa-folder-open mr-1"></i> Belum ada proyek.</div>
                @endforelse
            </div>
        </div>

        @if(isset($recentGalleries) && $recentGalleries->count())
        <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-[#E8DFD1]">
                <h3 class="font-semibold text-[13px] flex items-center gap-2"><i class="fa-solid fa-images text-[#7A7670]"></i> Gallery Terbaru</h3>
                <a href="{{ route('galleries.index') }}" class="text-[12px] text-[#7A7670] hover:text-[#11100F]">Kelola</a>
            </div>
            <div class="grid grid-cols-3 gap-2 p-3">
                @foreach($recentGalleries as $g)
                <div class="aspect-[4/3] rounded-lg overflow-hidden border border-[#E8DFD1] bg-[#F6F5F1]"><img src="{{ $g->image_url }}" class="w-full h-full object-cover"></div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="space-y-4">
        <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-[#E8DFD1]">
                <h3 class="font-semibold text-[13px] flex items-center gap-2"><i class="fa-solid fa-users text-[#7A7670]"></i> Mahasiswa Terbaru</h3>
                <a href="{{ route('students.index') }}" class="text-[12px] text-[#7A7670] hover:text-[#11100F]">Semua</a>
            </div>
            <div class="divide-y divide-[#E8DFD1]/60">
                @forelse($recentStudents as $s)
                <div class="flex items-center gap-3 px-5 py-3">
                    <img src="{{ $s->photo_url ?? 'https://api.dicebear.com/7.x/initials/svg?seed='.urlencode($s->name).'&backgroundColor=F5EFE6' }}" class="w-8 h-8 rounded-full border border-[#E8DFD1] bg-white object-cover">
                    <div class="min-w-0 flex-1">
                        <div class="text-[13px] font-medium truncate leading-none">{{ $s->name }}</div>
                        <div class="font-mono text-[11px] text-[#7A7670]">{{ $s->nim }}</div>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>
                @empty
                <div class="py-6 text-center text-[12px] text-[#7A7670]">Belum ada mahasiswa.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-[#E8DFD1]">
                <h3 class="font-semibold text-[13px] flex items-center gap-2"><i class="fa-solid fa-bullhorn text-[#7A7670]"></i> Pengumuman</h3>
                <span class="text-[11px] font-mono bg-[#11100F] text-white rounded-full px-2 py-0.5">{{ $announcementsCount }}</span>
            </div>
            <div class="divide-y divide-[#E8DFD1]/60">
                @forelse($recentAnnouncements as $a)
                <div class="px-5 py-3">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-mono tracking-wide uppercase px-2 py-0.5 rounded-full border {{ $a->category=='Urgent Deadline' ? 'bg-red-50 border-red-200 text-red-700' : 'bg-[#F6F5F1] border-[#E8DFD1] text-[#7A7670]' }}">{{ $a->category }}</span>
                        <span class="ml-auto font-mono text-[11px] text-[#7A7670]">{{ $a->event_date ? \Carbon\Carbon::parse($a->event_date)->format('d M') : 'TBA' }}</span>
                    </div>
                    <div class="text-[13px] font-medium leading-tight mt-1 truncate">{{ $a->title }}</div>
                </div>
                @empty
                <div class="py-6 text-center text-[12px] text-[#7A7670]">Belum ada pengumuman.</div>
                @endforelse
            </div>
            <div class="p-3">
                <a href="{{ route('announcements.create') }}" class="flex items-center justify-center gap-2 py-2.5 rounded-full border border-[#E8DFD1] bg-[#F6F5F1] text-[13px] font-medium hover:bg-white transition"><i class="fa-solid fa-plus text-[11px]"></i> Buat Pengumuman</a>
            </div>
        </div>
    </div>
</div>
@endsection
