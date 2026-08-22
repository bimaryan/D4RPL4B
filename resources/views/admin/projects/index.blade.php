@extends('layouts.admin')
@section('title', 'Karya')
@section('breadcrumb', 'Karya')
@section('content')
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-5">
    <div>
        <h1 class="font-semibold text-[18px] flex items-center gap-2"><span class="w-8 h-8 rounded-lg bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-layer-group text-[13px]"></i></span> Karya / Proyek <span class="font-normal text-[13px] text-[#7A7670]">— {{ $projects->count() }} proyek</span></h1>
        <p class="text-[12.5px] text-[#7A7670] mt-1">Showcase yang tampil di landing. Kelola cover, tech stack, dan tautan.</p>
    </div>
    <div class="flex items-center gap-2">
        <div class="hidden sm:flex items-center gap-2 bg-white border border-[#E8DFD1] rounded-full px-3 py-2">
            <i class="fa-solid fa-magnifying-glass text-[11px] text-[#7A7670]"></i>
            <input id="search-projects" data-page-search placeholder="Cari proyek..." class="bg-transparent text-[13px] outline-none w-[180px] placeholder:text-[#A8A29E]">
        </div>
        <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black transition"><i class="fa-solid fa-plus text-[11px]"></i> Tambah Karya</a>
    </div>
</div>

<div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden shadow-sm">
    <div class="flex items-center justify-between px-4 py-3 border-b border-[#E8DFD1] bg-[#FCFBF9]">
        <div class="flex items-center gap-2">
            <span class="text-[12px] font-medium">{{ $projects->count() }} Proyek</span>
            <span class="hidden sm:inline-flex items-center gap-1.5 text-[11px] border border-[#E8DFD1] rounded-full px-2.5 py-1 bg-white"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Live di landing</span>
        </div>
        <div class="flex items-center gap-1">
            <span class="hidden sm:inline text-[11px] font-mono text-[#7A7670]">GRID</span>
            <button class="w-8 h-8 rounded-lg bg-[#11100F] text-white flex items-center justify-center"><i class="fa-solid fa-table text-[11px]"></i></button>
            <button class="w-8 h-8 rounded-lg border border-[#E8DFD1] bg-white flex items-center justify-center text-[#7A7670]"><i class="fa-solid fa-grip text-[11px]"></i></button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left" data-searchable="search-projects">
            <thead class="bg-[#F6F5F1] border-b border-[#E8DFD1] text-[11px] font-semibold tracking-[0.08em] uppercase text-[#7A7670]">
                <tr>
                    <th class="px-4 py-3 w-8"><input type="checkbox" class="rounded border-[#E8DFD1]"></th>
                    <th class="px-3 py-3">Proyek</th>
                    <th class="px-3 py-3">Tech Stack</th>
                    <th class="px-3 py-3">Tautan</th>
                    <th class="px-3 py-3">Hosting</th>
                    <th class="px-3 py-3">ID Hash</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E8DFD1]/60 text-[13px]">
                @forelse($projects as $project)
                <tr class="hover:bg-[#FCFBF9]" data-row>
                    <td class="px-4 py-3"><input type="checkbox" class="rounded border-[#E8DFD1]"></td>
                    <td class="px-3 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[#F6F5F1] border border-[#E8DFD1] overflow-hidden flex items-center justify-center shrink-0">
                                @if($project->image_src)<img src="{{ $project->image_src }}" class="w-full h-full object-cover">@else<i class="fa-regular fa-image text-[#7A7670]"></i>@endif
                            </div>
                            <div class="min-w-0">
                                <div class="font-medium leading-none truncate max-w-[220px]">{{ $project->title }}</div>
                                <div class="text-[11px] text-[#7A7670] truncate max-w-[220px]">{{ \Illuminate\Support\Str::limit($project->description, 48) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 py-3">
                        @if($project->tech_stack && count($project->tech_stack))
                            <div class="flex flex-wrap gap-1 max-w-[200px]">
                                @foreach(array_slice($project->tech_stack,0,3) as $tech)
                                <span class="px-2 py-1 rounded-full bg-[#F6F5F1] border border-[#E8DFD1] text-[11px] font-medium">{{ $tech }}</span>
                                @endforeach
                                @if(count($project->tech_stack)>3)<span class="text-[11px] text-[#7A7670]">+{{count($project->tech_stack)-3}}</span>@endif
                            </div>
                        @else <span class="text-[#A8A29E] text-[12px]">—</span> @endif
                    </td>
                    <td class="px-3 py-3">
                        <div class="flex items-center gap-1">
                            @if($project->demo_url)<a href="{{ $project->demo_url }}" target="_blank" class="w-7 h-7 rounded-full bg-[#11100F] text-white flex items-center justify-center"><i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i></a>@endif
                            @if($project->repo_url)<a href="{{ $project->repo_url }}" target="_blank" class="w-7 h-7 rounded-full border border-[#E8DFD1] bg-white flex items-center justify-center"><i class="fa-brands fa-github text-[11px]"></i></a>@endif
                            @if(!$project->demo_url && !$project->repo_url)<span class="text-[#A8A29E] text-[12px]">—</span>@endif
                        </div>
                    </td>
                    <td class="px-3 py-3">
                        @if($project->portfolio_path)
                            <a href="{{ $project->portfolio_url }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[11px] font-medium"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Live</a>
                        @else
                            <span class="inline-flex px-2 py-1 rounded-full bg-[#F6F5F1] border border-[#E8DFD1] text-[#7A7670] text-[11px]">—</span>
                        @endif
                    </td>
                    <td class="px-3 py-3 font-mono text-[11px] text-[#7A7670]">{{ substr($project->hash_id,0,8) }}…</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('projects.edit', $project->hash_id) }}" class="px-3 py-1.5 rounded-full bg-white border border-[#E8DFD1] text-[12px] font-medium hover:bg-[#F6F5F1]"><i class="fa-solid fa-pen text-[10px] mr-1"></i> Edit</a>
                            <form action="{{ route('projects.destroy', $project->hash_id) }}" method="POST" onsubmit="return confirmDelete(this, 'Hapus Data?', 'Hapus {{ $project->title }}? Yakin ingin melanjutkan? Tindakan tidak bisa dibatalkan')">
                                @csrf @method('DELETE')
                                <button class="w-8 h-8 rounded-full bg-white border border-red-200 text-red-600 flex items-center justify-center hover:bg-red-50"><i class="fa-solid fa-trash text-[11px]"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr data-empty><td colspan="7" class="px-6 py-12 text-center">
                    <div class="w-12 h-12 rounded-full bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center mx-auto mb-3"><i class="fa-solid fa-layer-group text-[#7A7670]"></i></div>
                    <div class="text-[13px] font-medium">Belum ada karya</div>
                    <div class="text-[12px] text-[#7A7670]">Klik Tambah Karya untuk publish pertama.</div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="flex items-center justify-between px-4 py-3 border-t border-[#E8DFD1] bg-[#FCFBF9] text-[12px] text-[#7A7670]">
        <span><span data-count>{{ $projects->count() }}</span> proyek total</span>
        <div class="flex items-center gap-1"><span class="px-3 py-1.5 rounded-full bg-[#11100F] text-white">1</span></div>
    </div>
</div>
@endsection
