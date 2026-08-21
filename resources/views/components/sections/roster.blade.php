@props(['students'])

<section id="roster" class="py-16 lg:py-24 border-t border-[#E8DFD1] bg-white/60 scroll-mt-[72px]">
    <div class="max-w-[1280px] mx-auto px-6 lg:px-8">
        <div data-motion="section-header" class="flex flex-col lg:flex-row lg:items-end justify-between gap-5 mb-10">
            <div class="max-w-[560px]">
                <div class="inline-flex items-center gap-2 font-mono text-[11px] tracking-[0.14em] uppercase text-[#E84E0F]"><span class="w-6 h-[1px] bg-[#E84E0F]"></span> Daftar Anggota</div>
                <h2 class="font-display text-[30px] lg:text-[38px] font-[600] tracking-[-0.03em] leading-[0.95] mt-3">30 engineer<span class="font-serif italic font-[400] text-[#7A7670]"> — satu kelas.</span></h2>
                <p class="text-[14px] leading-[1.6] tracking-[-0.01em] text-[#6E6A64] mt-3">Dari front-end sampai data, setiap orang punya spesialisasi. Klik portfolio atau hubungi langsung via GitHub / LinkedIn.</p>
            </div>
            <div data-motion="roster-badge" class="hidden lg:inline-flex items-center gap-2 h-[32px] font-mono text-[11px] tracking-[0.02em] border border-[#E8DFD1] rounded-full px-3.5 bg-white text-[#7A7670] shrink-0">
                <i class="fa-solid fa-arrow-down-wide-short text-[#E84E0F] text-[11px]"></i> Urut NIM · Polindra
            </div>
        </div>

        <div data-motion="roster-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($students as $student)
            <div class="motion-roster-card group bg-white border border-[#E8DFD1] rounded-[16px] p-4 flex gap-4 hover:border-[#D6CFC2] hover:shadow-[0_8px_24px_rgba(20,18,16,0.06)] hover:-translate-y-[1px] transition-all duration-200 will-change-transform">
                @if($student->photo_url)
                    <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="w-[52px] h-[52px] rounded-full border border-[#E8DFD1] object-cover bg-[#FDF9F3] shrink-0">
                @else
                    @php
                        $initials = collect(explode(' ', trim($student->name)))->filter()->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->implode('');
                        $initials = $initials ?: '—';
                    @endphp
                    <div class="w-[52px] h-[52px] rounded-full border border-[#E8DFD1] bg-[#F5EFE6] flex items-center justify-center shrink-0">
                        <span class="font-display font-[600] text-[14px] tracking-[-0.02em] text-[#141210]">{{ $initials }}</span>
                    </div>
                @endif
                <div class="min-w-0 flex-1 flex flex-col">
                    <div class="font-mono text-[10.5px] tracking-[0.08em] leading-none text-[#E84E0F] uppercase">{{ $student->nim }}</div>
                    <div class="text-[13.5px] font-[600] leading-[1.25] tracking-[-0.01em] text-[#141210] truncate mt-1">{{ $student->name }}</div>
                    <div class="flex items-center gap-2 mt-auto pt-3">
                        @if($student->github_url)
                        <a href="{{ $student->github_url }}" target="_blank" class="w-[28px] h-[28px] rounded-full border border-[#E8DFD1] bg-[#FDF9F3] flex items-center justify-center hover:bg-[#141210] hover:text-white hover:border-[#141210] transition" title="GitHub">
                            <i class="fa-brands fa-github text-[12px]"></i>
                        </a>
                        @else
                        <span class="w-[28px] h-[28px] rounded-full border border-dashed border-[#E8DFD1] flex items-center justify-center text-[#C9C3B8]"><i class="fa-brands fa-github text-[11px]"></i></span>
                        @endif
                        @if($student->linkedin_url)
                        <a href="{{ $student->linkedin_url }}" target="_blank" class="w-[28px] h-[28px] rounded-full border border-[#E8DFD1] bg-[#FDF9F3] flex items-center justify-center hover:bg-[#0A66C2] hover:text-white hover:border-[#0A66C2] transition" title="LinkedIn">
                            <i class="fa-brands fa-linkedin-in text-[11px]"></i>
                        </a>
                        @else
                        <span class="w-[28px] h-[28px] rounded-full border border-dashed border-[#E8DFD1] flex items-center justify-center text-[#C9C3B8]"><i class="fa-brands fa-linkedin-in text-[11px]"></i></span>
                        @endif
                        <a href="{{ $student->portfolio_url ?? '#' }}" class="ml-auto inline-flex items-center gap-1 text-[11px] font-[500] tracking-[0.02em] uppercase text-[#7A7670] hover:text-[#141210] transition">Portfolio <i class="fa-solid fa-arrow-up-right-from-square text-[9px] translate-y-[-1px]"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
