@props(['projects'])

<section id="projects" class="py-16 lg:py-24 border-t border-[#E8DFD1] scroll-mt-[72px]">
    <div class="max-w-[1280px] mx-auto px-6 lg:px-8">
        <div data-motion="section-header" class="flex flex-col lg:flex-row lg:items-end justify-between gap-5 mb-10">
            <div class="max-w-[560px]">
                <div class="inline-flex items-center gap-2 font-mono text-[11px] tracking-[0.14em] uppercase text-[#E84E0F]"><span class="w-6 h-[1px] bg-[#E84E0F]"></span> Karya Terpilih</div>
                <h2 class="font-display text-[30px] lg:text-[38px] font-[600] tracking-[-0.03em] leading-[0.95] mt-3">Produk yang kami <span class="font-serif italic font-[400] text-[#7A7670]">ship.</span></h2>
                <p class="text-[14px] leading-[1.6] tracking-[-0.01em] text-[#6E6A64] mt-3">Bukan tugas kuliah biasa — aplikasi yang dipakai, diuji, dan dipresentasikan ke industri.</p>
            </div>
            <a href="#roster" class="hidden lg:inline-flex items-center gap-1.5 h-[36px] px-4 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-[500] hover:bg-[#F5EFE6] transition">Semua proyek <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
        </div>

        @if($projects->isEmpty())
            <div data-motion="section-header" class="rounded-[16px] border border-dashed border-[#E8DFD1] bg-white py-14 text-center">
                <div class="w-10 h-10 rounded-full bg-[#FDF9F3] border border-[#E8DFD1] flex items-center justify-center mx-auto"><i class="fa-regular fa-folder-open text-[#7A7670] text-[14px]"></i></div>
                <div class="font-display text-[16px] font-[500] mt-3">Belum ada proyek dipublikasi</div>
                <div class="text-[13px] leading-[1.5] text-[#7A7670] mt-1">Admin dapat menambahkan dari dashboard.</div>
            </div>
        @else
        <div data-motion="projects-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($projects as $project)
            <article class="motion-project-card group bg-white border border-[#E8DFD1] rounded-[16px] overflow-hidden hover:shadow-[0_12px_32px_rgba(20,18,16,0.08)] hover:border-[#D6CFC2] hover:-translate-y-[2px] transition-all duration-300 flex flex-col will-change-transform">
                <div class="aspect-[16/10] bg-[#F5EFE6] border-b border-[#E8DFD1] overflow-hidden relative">
                    @if($project->image_src)
                        <img src="{{ $project->image_src }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition duration-500 will-change-transform">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <div class="w-12 h-12 rounded-xl bg-white border border-[#E8DFD1] flex items-center justify-center text-[#C9C3B8]">
                                <i class="fa-regular fa-image text-[16px]"></i>
                            </div>
                        </div>
                    @endif
                    <div class="absolute top-3 left-3 flex gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-white/90 border border-black/10 shadow-sm"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-white/60 border border-black/10"></span>
                    </div>
                </div>
                <div class="p-5 flex flex-col flex-1">
                    <h3 class="font-display text-[17px] font-[600] leading-[1.25] tracking-[-0.015em] line-clamp-1 group-hover:text-[#E84E0F] transition-colors">{{ $project->title }}</h3>
                    <p class="text-[13.5px] leading-[1.6] tracking-[-0.01em] text-[#6E6A64] mt-2 line-clamp-3 flex-1">{{ $project->description }}</p>
                    
                    @if($project->tech_stack && is_array($project->tech_stack))
                    <div class="flex flex-wrap gap-1.5 mt-4">
                        @foreach(array_slice($project->tech_stack, 0, 4) as $tech)
                        <span class="h-[24px] inline-flex items-center px-2.5 rounded-full bg-[#FDF9F3] border border-[#E8DFD1] text-[11px] font-[500] tracking-[-0.01em] text-[#4A4743]"><i class="fa-solid fa-code text-[9px] mr-1 opacity-60"></i>{{ $tech }}</span>
                        @endforeach
                        @if(count($project->tech_stack) > 4)
                        <span class="h-[24px] inline-flex items-center px-2.5 text-[11px] text-[#7A7670]">+{{ count($project->tech_stack)-4 }}</span>
                        @endif
                    </div>
                    @endif

                    <div class="flex items-center gap-2 mt-5 pt-4 border-t border-[#E8DFD1]">
                        <a href="{{ $project->demo_url ?? '#' }}" target="_blank" class="flex-1 inline-flex items-center justify-center gap-1.5 h-[40px] rounded-full bg-[#141210] text-white text-[13px] font-[500] hover:bg-black transition">Live Demo <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i></a>
                        <a href="{{ $project->repo_url ?? '#' }}" target="_blank" class="w-10 h-10 rounded-full border border-[#E8DFD1] bg-white flex items-center justify-center hover:bg-[#141210] hover:text-white hover:border-[#141210] transition" title="Repository">
                            <i class="fa-brands fa-github text-[14px]"></i>
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        @endif
    </div>
</section>
