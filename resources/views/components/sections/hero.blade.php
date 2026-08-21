<section class="pt-[64px] lg:pt-[68px] scroll-mt-[72px]">
    <div class="max-w-[1280px] mx-auto px-6 lg:px-8">
        <!-- Top meta bar — 44px presisi -->
        <div class="flex items-center justify-between h-[44px] border-b border-[#E8DFD1] text-[11px] font-mono tracking-[0.08em] uppercase text-[#7A7670]">
            <span class="hidden sm:inline tracking-[0.1em]">Politeknik Negeri Indramayu — Jurusan Teknik Informatika</span>
            <span class="sm:hidden">Polindra — TI</span>
            <span class="inline-flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#E84E0F] animate-pulse"></span> Angkatan 2022 · Semester 8</span>
        </div>

        <div class="grid lg:grid-cols-12 gap-8 lg:gap-10 pt-10 lg:pt-14 pb-12 lg:pb-16">
            <!-- Left — 7 col presisi -->
            <div class="lg:col-span-7 flex flex-col">
                <div data-motion="hero-badge" class="inline-flex items-center gap-2 self-start rounded-full border border-[#E8DFD1] bg-white px-3.5 py-1.5 text-[11px] font-mono tracking-[0.02em] text-[#7A7670] shadow-[0_1px_4px_rgba(0,0,0,0.04)]">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Penerimaan karya terbuka — TA Genap 2026
                </div>

                <h1 data-motion="hero-title" class="font-display font-[600] tracking-[-0.04em] leading-[0.88] mt-6">
                    <span class="block text-[42px] sm:text-[54px] lg:text-[68px] xl:text-[72px] text-[#141210]">Rekayasa</span>
                    <span class="block text-[42px] sm:text-[54px] lg:text-[68px] xl:text-[72px] text-[#141210] -mt-[2px]">Perangkat</span>
                    <span class="block font-serif italic font-[400] text-[38px] sm:text-[50px] lg:text-[62px] xl:text-[66px] text-[#E84E0F] -mt-[2px] tracking-[-0.02em]">Lunak 4B</span>
                </h1>

                <p data-motion="hero-desc" class="mt-5 max-w-[520px] text-[15px] lg:text-[16px] leading-[1.7] tracking-[-0.01em] text-[#4A4743]">
                    Kami 30 mahasiswa D4 Rekayasa Perangkat Lunak yang membangun produk digital — dari riset hingga deployment. Portofolio hidup perjalanan akademik, karya, dan keseharian kami di Polindra.
                </p>

                <div data-motion="hero-cta" class="flex flex-wrap gap-3 mt-8">
                    <a href="#projects" class="inline-flex items-center gap-2 h-[44px] px-6 rounded-full bg-[#141210] text-white text-[13.5px] font-[500] tracking-[-0.01em] hover:bg-black transition will-change-transform">
                        Lihat Karya <i class="fa-solid fa-arrow-up-right-from-square text-[11px] translate-y-[-1px]"></i>
                    </a>
                    <a href="#roster" class="inline-flex items-center gap-2 h-[44px] px-6 rounded-full bg-white border border-[#E8DFD1] text-[#141210] text-[13.5px] font-[500] hover:bg-[#F5EFE6] hover:border-[#D6CFC2] transition">
                        <i class="fa-solid fa-users text-[11px]"></i> Kenalan dengan 4B
                    </a>
                </div>

                <!-- Stats — 8pt grid presisi -->
                <div data-motion="hero-stats" class="grid grid-cols-3 gap-0 mt-10 pt-6 border-t border-[#E8DFD1] max-w-[520px]">
                    <div class="pr-6">
                        <div class="font-display text-[28px] leading-none font-[600] tracking-[-0.02em]">30</div>
                        <div class="font-mono text-[10.5px] tracking-[0.1em] uppercase text-[#7A7670] mt-1.5">Mahasiswa</div>
                    </div>
                    <div class="border-l border-[#E8DFD1] pl-6">
                        <div class="font-display text-[28px] leading-none font-[600] tracking-[-0.02em]">12+</div>
                        <div class="font-mono text-[10.5px] tracking-[0.1em] uppercase text-[#7A7670] mt-1.5">Proyek Rilis</div>
                    </div>
                    <div class="border-l border-[#E8DFD1] pl-6">
                        <div class="font-display text-[28px] leading-none font-[600] tracking-[-0.02em]">4<span class="text-[16px] font-[500]">th</span></div>
                        <div class="font-mono text-[10.5px] tracking-[0.1em] uppercase text-[#7A7670] mt-1.5">Tahun Studi</div>
                    </div>
                </div>
            </div>

            <!-- Right Visual — 5 col presisi -->
            <div data-motion="hero-visual" class="lg:col-span-5 lg:pl-2">
                <div class="relative bg-white rounded-[20px] border border-[#E8DFD1] overflow-hidden shadow-[0_8px_32px_rgba(20,18,16,0.06)]">
                    <div class="flex items-center justify-between h-[40px] px-4 border-b border-[#E8DFD1] bg-[#FDF9F3]">
                        <div class="flex items-center gap-1.5">
                            <span class="w-[12px] h-[12px] rounded-full bg-[#FF5F56] border border-black/10"></span>
                            <span class="w-[12px] h-[12px] rounded-full bg-[#FFBD2E] border border-black/10"></span>
                            <span class="w-[12px] h-[12px] rounded-full bg-[#27C93F] border border-black/10"></span>
                        </div>
                        <span class="font-mono text-[11px] tracking-[-0.01em] text-[#7A7670]">studio-4b.tsx — Edited</span>
                    </div>
                    <div class="aspect-[4/3.05] relative bg-[#F5EFE6] overflow-hidden">
                        <img src="{{ \App\Models\Setting::heroImageUrl() }}" alt="Kelas 4B" class="w-full h-full object-cover mix-blend-multiply opacity-[0.92] will-change-transform">
                        <div class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-black/60 to-transparent">
                            <div class="inline-flex items-center gap-2 bg-white rounded-full px-3 py-1.5 text-[11px] font-[500] shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Lab. Software 1 — Indramayu
                            </div>
                        </div>
                    </div>
                    <div class="h-[52px] px-4 flex flex-col justify-center bg-[#141210] text-[#E8DFD1] font-mono text-[12px] leading-[1.5]">
                        <div><span class="text-[#7A7670]">01</span> <span class="text-[#FF7A45]">const</span> cohort = <span class="text-[#E8DFD1]">"D4 RPL 4B"</span>;</div>
                        <div><span class="text-[#7A7670]">02</span> <span class="text-[#FF7A45]">await</span> cohort.<span class="text-[#7DD3FC]">ship</span>({ <span class="text-[#E8DFD1]">quality: "craft"</span> });</div>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-3 text-[11px] font-mono tracking-[0.02em] text-[#7A7670]">
                    <span class="hidden sm:inline">{{ \App\Models\Setting::heroCaption() }}</span>
                    <span class="sm:hidden">{{ str(\App\Models\Setting::heroCaption())->limit(25) }}</span>
                    <span class="ml-auto hidden sm:inline-flex items-center gap-1.5 border border-[#E8DFD1] rounded-full px-2.5 py-1 bg-white"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Live update tiap minggu</span>
                </div>
            </div>
        </div>
    </div>
</section>
