@extends('layouts.admin')
@section('title', 'Tambah Karya')
@section('breadcrumb', 'Karya / Tambah')
@section('content')
<div class="max-w-[960px]">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('projects.index') }}" class="w-9 h-9 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-arrow-left text-[12px]"></i></a>
        <div><h1 class="font-semibold text-[18px] leading-none">Tambah Karya</h1><p class="text-[12.5px] text-[#7A7670]">Publish ke showcase landing — upload file, bukan URL.</p></div>
    </div>
    <div class="grid lg:grid-cols-[1fr_300px] gap-6">
        <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E8DFD1] bg-[#FCFBF9] flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-[#11100F] text-white flex items-center justify-center"><i class="fa-solid fa-layer-group text-[11px]"></i></span>
                <span class="font-medium text-[13px]">Form Karya</span>
            </div>
            <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                @if ($errors->any()) <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 text-[12.5px] px-4 py-3"><ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div> @endif
                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Judul Proyek *</label><input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: SI Akademik Polindra" class="form-input"></div>
                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Deskripsi *</label><textarea name="description" required rows="4" placeholder="Ceritakan fungsi & tech..." class="form-input">{{ old('description') }}</textarea></div>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Cover Upload * <span class="font-normal">JPG/PNG max 3MB</span></label>
                        <label class="flex items-center gap-3 border-2 border-dashed border-[#E8DFD1] rounded-xl p-4 bg-[#FCFBF9] hover:bg-white cursor-pointer">
                            <span class="w-10 h-10 rounded-lg bg-white border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-cloud-arrow-up text-[#7A7670]"></i></span>
                            <div class="flex-1 min-w-0">
                                <div class="text-[13px] font-medium">Pilih / Drag file</div>
                                <div class="text-[11px] text-[#7A7670] truncate" id="proj-file-name">Belum ada file</div>
                            </div>
                            <input type="file" name="image" accept="image/*" class="hidden" onchange="document.getElementById('proj-file-name').textContent = this.files[0] ? this.files[0].name : 'Belum ada file'">
                        </label>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Atau Image URL <span class="font-normal">(opsional)</span></label>
                        <div class="relative"><span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#7A7670]"><i class="fa-solid fa-link text-[11px]"></i></span><input type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://..." class="form-input pl-9"></div>
                        <div class="text-[11px] text-[#7A7670] mt-1">Prioritas file upload.</div>
                    </div>
                </div>

                <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Tech Stack (pisah koma)</label><div class="relative"><span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#7A7670]"><i class="fa-solid fa-code text-[11px]"></i></span><input type="text" name="tech_stack" value="{{ old('tech_stack') }}" placeholder="Laravel, Tailwind, MySQL" class="form-input pl-9"></div></div>

                <div class="border border-[#E8DFD1] rounded-xl p-4 bg-[#FFFBF0] space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-[#11100F] text-white flex items-center justify-center"><i class="fa-solid fa-server text-[11px]"></i></span>
                        <div>
                            <div class="font-medium text-[13px]">Portfolio Hosting (Full Web Server)</div>
                            <div class="text-[11px] text-[#7A7670]">Upload ZIP berisi website statis — otomatis di-host</div>
                        </div>
                    </div>
                    <label class="flex items-center gap-3 border-2 border-dashed border-[#E8DFD1] rounded-xl p-4 bg-white hover:bg-[#FCFBF9] cursor-pointer">
                        <span class="w-10 h-10 rounded-lg bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-file-zipper text-[#7A7670]"></i></span>
                        <div class="flex-1 min-w-0">
                            <div class="text-[13px] font-medium">Upload Portfolio ZIP</div>
                            <div class="text-[11px] text-[#7A7670] truncate" id="portfolio-file">Belum ada file • max 50MB • harus ada index.html</div>
                        </div>
                        <input type="file" name="portfolio" accept=".zip" class="hidden" onchange="document.getElementById('portfolio-file').textContent=this.files[0]?this.files[0].name+' ('+Math.round(this.files[0].size/1024)+' KB)':'Belum ada file • max 50MB • harus ada index.html'">
                    </label>
                    <div class="text-[11px] text-[#7A7670] flex items-center gap-1.5"><i class="fa-solid fa-circle-info text-[10px]"></i> ZIP akan di-extract ke <span class="font-mono bg-white border border-[#E8DFD1] rounded px-1.5 py-0.5">/portfolio/{hash}</span> dan serve sebagai web statis.</div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Live Demo URL <span class="font-normal normal-case">(jika tidak hosting)</span></label><input type="url" name="demo_url" value="{{ old('demo_url') }}" placeholder="https://..." class="form-input"></div>
                    <div><label class="text-[11px] font-semibold tracking-[0.06em] uppercase text-[#7A7670] mb-1.5 block">Repository URL</label><input type="url" name="repo_url" value="{{ old('repo_url') }}" placeholder="https://github.com/..." class="form-input"></div>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-[#E8DFD1]">
                    <a href="{{ route('projects.index') }}" class="px-5 py-2.5 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-medium">Batal</a>
                    <button class="px-6 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black transition"><i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Karya</button>
                </div>
            </form>
        </div>
        <div class="space-y-4">
            <div class="bg-white border border-[#E8DFD1] rounded-xl p-5">
                <div class="text-[11px] font-semibold tracking-[0.08em] uppercase text-[#7A7670]">Pratinjau</div>
                <div class="mt-3 aspect-[16/10] bg-[#F6F5F1] border border-dashed border-[#E8DFD1] rounded-lg flex flex-col items-center justify-center text-[#A8A29E] gap-2">
                    <i class="fa-regular fa-image text-[20px]"></i>
                    <span class="text-[11px]">16:10 • Upload file</span>
                </div>
                <div class="text-[12px] text-[#7A7670] mt-2">File akan disimpan di storage, tampil otomatis di landing.</div>
            </div>
            <div class="bg-[#11100F] text-white rounded-xl p-5">
                <div class="text-[13px] font-medium flex items-center gap-2"><i class="fa-solid fa-circle-info text-[#7DD3FC]"></i> Tips</div>
                <p class="text-[12px] text-white/70 mt-1">Upload langsung, jangan pakai URL panjang. Max 3MB, WebP disarankan.</p>
            </div>
        </div>
    </div>
</div>
@endsection
