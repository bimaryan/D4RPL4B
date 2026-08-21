@extends('layouts.admin')
@section('title', 'Edit Karya')
@section('breadcrumb', 'Karya / Edit')
@section('content')
<div class="max-w-[960px]">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('projects.index') }}" class="w-9 h-9 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-arrow-left text-[12px]"></i></a>
        <div class="flex-1"><h1 class="font-semibold text-[18px] leading-none">Edit Karya</h1><p class="text-[11px] font-mono text-[#7A7670]">Hash: {{ $project->hash_id }}</p></div>
        <form action="{{ route('projects.destroy', $project->hash_id) }}" method="POST" onsubmit="return confirmDelete(this, 'Hapus Data?', 'Hapus proyek ini? Yakin ingin melanjutkan? Tindakan tidak bisa dibatalkan')">@csrf @method('DELETE')<button class="px-4 py-2 rounded-full bg-white border border-red-200 text-red-600 text-[12px] font-medium hover:bg-red-50"><i class="fa-solid fa-trash mr-1"></i> Hapus</button></form>
    </div>
    <div class="grid lg:grid-cols-[1fr_300px] gap-6">
        <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E8DFD1] bg-[#FCFBF9] flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-pen text-[11px]"></i></span>
                <span class="font-medium text-[13px]">Edit Karya</span>
            </div>
            <form action="{{ route('projects.update', $project->hash_id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf @method('PUT')
                @if ($errors->any()) <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 text-[12.5px] px-4 py-3"><ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div> @endif
                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Judul *</label><input type="text" name="title" value="{{ old('title', $project->title) }}" required class="form-input"></div>
                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Deskripsi *</label><textarea name="description" required rows="4" class="form-input">{{ old('description', $project->description) }}</textarea></div>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Ganti Cover (Upload)</label>
                        <label class="flex items-center gap-3 border-2 border-dashed border-[#E8DFD1] rounded-xl p-3 bg-[#FCFBF9] hover:bg-white cursor-pointer">
                            <span class="w-10 h-10 rounded-lg bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-cloud-arrow-up text-[#7A7670]"></i></span>
                            <div class="flex-1 min-w-0">
                                <div class="text-[12px] font-medium">Upload baru</div>
                                <div class="text-[11px] text-[#7A7670] truncate" id="edit-file-name">Belum ada file baru</div>
                            </div>
                            <input type="file" name="image" accept="image/*" class="hidden" onchange="document.getElementById('edit-file-name').textContent = this.files[0] ? this.files[0].name : 'Belum ada file baru'">
                        </label>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Atau Image URL</label>
                        <input type="url" name="image_url" value="{{ old('image_url', $project->image_url && str_starts_with($project->image_url, 'http') ? $project->image_url : '') }}" placeholder="https://..." class="form-input">
                        <label class="flex items-center gap-1.5 mt-2 text-[12px]"><input type="checkbox" name="remove_image" value="1" class="rounded"> Hapus cover</label>
                    </div>
                </div>

                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Tech Stack</label><input type="text" name="tech_stack" value="{{ old('tech_stack', is_array($project->tech_stack) ? implode(', ', $project->tech_stack) : '') }}" class="form-input"></div>

                <div class="border border-[#E8DFD1] rounded-xl p-4 bg-[#FFFBF0] space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-[#11100F] text-white flex items-center justify-center"><i class="fa-solid fa-server text-[11px]"></i></span>
                            <div>
                                <div class="font-medium text-[13px]">Portfolio Hosting</div>
                                <div class="text-[11px] text-[#7A7670]">ZIP → /portfolio/{{ $project->hash_id }}</div>
                            </div>
                        </div>
                        @if($project->portfolio_path)
                            <a href="{{ $project->portfolio_url }}" target="_blank" class="text-[11px] font-medium bg-emerald-500 text-white px-2.5 py-1 rounded-full"><i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Live</a>
                        @else
                            <span class="text-[11px] bg-white border border-[#E8DFD1] rounded-full px-2.5 py-1 text-[#7A7670]">Belum ada</span>
                        @endif
                    </div>
                    @if($project->portfolio_path)
                        <div class="flex items-center gap-2 text-[11px] font-mono bg-white border border-[#E8DFD1] rounded-lg px-3 py-2">
                            <i class="fa-solid fa-link text-[#7A7670]"></i>
                            <a href="{{ $project->portfolio_url }}" target="_blank" class="truncate text-[#2563EB] hover:underline">{{ $project->portfolio_url }}</a>
                            <span class="ml-auto text-[#7A7670]">{{ $project->portfolio_path }}</span>
                        </div>
                    @endif
                    <label class="flex items-center gap-3 border-2 border-dashed border-[#E8DFD1] rounded-xl p-3 bg-white hover:bg-[#FCFBF9] cursor-pointer">
                        <span class="w-10 h-10 rounded-lg bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-file-zipper text-[#7A7670]"></i></span>
                        <div class="flex-1 min-w-0">
                            <div class="text-[12px] font-medium">Ganti / Upload ZIP</div>
                            <div class="text-[11px] text-[#7A7670] truncate" id="edit-portfolio">Belum ada file baru • max 50MB</div>
                        </div>
                        <input type="file" name="portfolio" accept=".zip" class="hidden" onchange="document.getElementById('edit-portfolio').textContent=this.files[0]?this.files[0].name+' ('+Math.round(this.files[0].size/1024)+' KB)':'Belum ada file baru • max 50MB'">
                    </label>
                    @if($project->portfolio_path)
                        <label class="flex items-center gap-2 text-[12px]"><input type="checkbox" name="remove_portfolio" value="1" class="rounded"> Hapus hosting portfolio</label>
                    @endif
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Demo URL <span class="font-normal">(alternatif)</span></label><input type="url" name="demo_url" value="{{ old('demo_url', $project->demo_url) }}" placeholder="https://..." class="form-input"></div>
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Repo URL</label><input type="url" name="repo_url" value="{{ old('repo_url', $project->repo_url) }}" class="form-input"></div>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-[#E8DFD1]">
                    <a href="{{ route('projects.index') }}" class="px-5 py-2.5 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-medium">Batal</a>
                    <button class="px-6 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black transition"><i class="fa-solid fa-floppy-disk mr-1.5"></i> Update</button>
                </div>
            </form>
        </div>
        <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
            @php $src = $project->image_src; @endphp
            @if($src)<img src="{{ $src }}" class="w-full aspect-[16/10] object-cover">@else<div class="aspect-[16/10] bg-[#F6F5F1] flex items-center justify-center text-[#A8A29E]"><i class="fa-regular fa-image text-[24px]"></i></div>@endif
            <div class="p-4"><div class="font-medium text-[13px] truncate">{{ $project->title }}</div><div class="font-mono text-[11px] text-[#7A7670]">{{ substr($project->hash_id,0,10) }}</div>@if($project->image_url)<div class="font-mono text-[10px] text-[#7A7670] mt-1 truncate">{{ $project->image_url }}</div>@endif</div>
        </div>
    </div>
</div>
@endsection
