@props(['galleries' => collect()])

<section id="gallery" class="py-16 lg:py-24 border-t border-[#E8DFD1] scroll-mt-[72px]" x-data="{ lightboxOpen: false, activeImage: '', activeCaption: '' }">
    <div class="max-w-[1280px] mx-auto px-6 lg:px-8">
        <div data-motion="section-header" class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-8">
            <div>
                <div class="inline-flex items-center gap-2 font-mono text-[11px] tracking-[0.14em] uppercase text-[#E84E0F]"><span class="w-6 h-[1px] bg-[#E84E0F]"></span> Di Balik Layar</div>
                <h2 class="font-display text-[30px] sm:text-[38px] font-semibold tracking-tight mt-2 leading-none">Hidup di <span class="font-serif italic font-normal text-[#7A7670]">4B</span></h2>
                <p class="text-[14px] text-[#6E6A64] mt-2">Bukan cuma ngoding — kebersamaan, lomba, dan cerita kampus.</p>
            </div>
            <div class="font-mono text-[11px] text-[#7A7670] border border-[#E8DFD1] rounded-full px-3 py-1.5 bg-white hidden lg:inline-flex">2022 — 2026 • Indramayu</div>
        </div>

        @if($galleries->isEmpty())
            <div data-motion="section-header" class="rounded-[16px] border border-dashed border-[#E8DFD1] bg-white p-10 text-center">
                <div class="w-12 h-12 rounded-full bg-[#FDF9F3] border border-[#E8DFD1] flex items-center justify-center mx-auto mb-3"><i class="fa-regular fa-images text-[#7A7670]"></i></div>
                <div class="font-display text-[18px] font-medium">Belum ada foto gallery</div>
                <div class="text-[13px] text-[#7A7670] mt-1">Admin dapat menambah via panel Gallery.</div>
            </div>
        @else
        <div data-motion="gallery-grid" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-4 auto-rows-[200px]">
            @foreach($galleries as $idx => $item)
                @php
                    // layout logic: first large, last wide if >=4, else standard
                    $cls = 'motion-gallery-item rounded-[18px] overflow-hidden border border-[#E8DFD1] relative group cursor-pointer will-change-transform';
                    if ($galleries->count() >= 4) {
                        if ($idx === 0) $cls .= ' sm:col-span-2 lg:col-span-2 lg:row-span-2';
                        elseif ($idx === $galleries->count()-1) $cls .= ' sm:col-span-2 lg:col-span-2';
                    }
                @endphp
                <div @click="activeImage = '{{ $item->image_url }}'; activeCaption = '{{ addslashes($item->title . ($item->caption ? ' — '.$item->caption : '')) }}'; lightboxOpen = true" class="{{ $cls }}">
                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-[1.02] transition duration-700">
                    @if($idx === 0 || $idx === $galleries->count()-1)
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/5 to-transparent"></div>
                        <div class="absolute bottom-0 p-5 text-white">
                            <div class="font-display text-[16px] font-medium leading-none">{{ $item->title }}</div>
                            @if($item->caption)<div class="font-mono text-[11px] opacity-80 mt-1">{{ $item->caption }}</div>@endif
                        </div>
                    @else
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition"></div>
                        <span class="absolute bottom-3 left-3 bg-white rounded-full px-2.5 py-1 font-mono text-[11px]">{{ $item->title }}</span>
                    @endif
                </div>
            @endforeach
        </div>
        @endif
    </div>

    <div x-show="lightboxOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 backdrop-blur-md p-4 sm:p-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" @keydown.escape.window="lightboxOpen = false" @click.self="lightboxOpen = false">
        <button @click="lightboxOpen = false" class="absolute top-6 right-6 text-white hover:text-red-400 transition-colors p-2 bg-white/10 hover:bg-white/20 rounded-full z-50"><i class="fa-solid fa-xmark text-[18px]"></i></button>
        <div class="max-w-5xl w-full flex flex-col items-center gap-4 relative z-40">
            <img :src="activeImage" alt="Gallery preview" class="w-auto h-auto max-h-[80vh] object-contain rounded-xl shadow-2xl">
            <p class="text-white text-sm md:text-base font-semibold tracking-wide" x-text="activeCaption"></p>
        </div>
    </div>
</section>
