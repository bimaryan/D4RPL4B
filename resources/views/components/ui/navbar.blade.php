<nav data-motion="navbar"
     x-data="{ open:false, scrolled:false, active:'' }"
     x-init="
        scrolled = window.scrollY > 6;
        const ids=['roster','projects','academic','gallery'];
        const obs=new IntersectionObserver((es)=>es.forEach(e=>{if(e.isIntersecting) active=e.target.id}),{rootMargin:'-48% 0px -52% 0px',threshold:0});
        ids.forEach(id=>{const el=document.getElementById(id); if(el) obs.observe(el)});
     "
     @scroll.window="scrolled=window.scrollY>6"
     @keydown.escape.window="open=false"
     :class="scrolled ? 'bg-[#FDF9F3]/90 backdrop-blur-xl border-[#E8DFD1] shadow-[0_1px_16px_rgba(20,18,16,0.06)]' : 'bg-[#FDF9F3] border-[#E8DFD1]/60'"
     class="fixed top-0 inset-x-0 z-50 border-b transition-all duration-300"
     role="navigation" aria-label="Navigasi utama">
    <div class="max-w-[1280px] mx-auto px-6 lg:px-8 h-[64px] flex items-center justify-between gap-6">
        <a href="#" @click.prevent="window.scrollTo({top:0,behavior:'smooth'})" class="flex items-center gap-3 group shrink-0">
            <div class="w-[30px] h-[30px] rounded-[8px] bg-[#141210] flex items-center justify-center text-white font-mono text-[10.5px] font-[700] tracking-tight">4B</div>
            <div class="hidden sm:block leading-none">
                <div class="font-display font-[600] text-[15px] tracking-[-0.02em] leading-none">D4 RPL 4B</div>
                <div class="font-mono text-[10px] tracking-[0.14em] uppercase text-[#7A7670] mt-[2px]">Polindra</div>
            </div>
            <div class="sm:hidden font-display font-[600] text-[14px] tracking-[-0.02em]">D4 RPL 4B</div>
        </a>

        <div class="hidden lg:flex items-center gap-7">
            <a href="#roster" @click="active='roster'" :class="active==='roster' ? 'text-[#141210]' : 'text-[#6E6A64] hover:text-[#141210]'" class="relative text-[14px] font-[450] tracking-[-0.01em] py-1 transition">
                Anggota
                <span x-show="active==='roster'" x-cloak class="absolute -bottom-[9px] left-0 right-0 h-[1.5px] bg-[#141210]"></span>
            </a>
            <a href="#projects" @click="active='projects'" :class="active==='projects' ? 'text-[#141210]' : 'text-[#6E6A64] hover:text-[#141210]'" class="relative text-[14px] font-[450] tracking-[-0.01em] py-1 transition">
                Karya
                <span x-show="active==='projects'" x-cloak class="absolute -bottom-[9px] left-0 right-0 h-[1.5px] bg-[#141210]"></span>
            </a>
            <a href="#academic" @click="active='academic'" :class="active==='academic' ? 'text-[#141210]' : 'text-[#6E6A64] hover:text-[#141210]'" class="relative text-[14px] font-[450] tracking-[-0.01em] py-1 transition">
                Akademik
                <span x-show="active==='academic'" x-cloak class="absolute -bottom-[9px] left-0 right-0 h-[1.5px] bg-[#141210]"></span>
            </a>
            <a href="#gallery" @click="active==='gallery' ? 'text-[#141210]' : 'text-[#6E6A64] hover:text-[#141210]'" :class="active==='gallery' ? 'text-[#141210]' : 'text-[#6E6A64] hover:text-[#141210]'" class="relative text-[14px] font-[450] tracking-[-0.01em] py-1 transition">
                Kehidupan
                <span x-show="active==='gallery'" x-cloak class="absolute -bottom-[9px] left-0 right-0 h-[1.5px] bg-[#141210]"></span>
            </a>
        </div>

        <div class="hidden lg:flex items-center gap-3 shrink-0">
            @if(Route::has('login'))
              @if(Auth::guard('web')->check() || Auth::guard('student')->check())
                @if(Auth::guard('web')->check() && Auth::guard('web')->user()->role === 'admin')
                  <a href="{{ route('admin.dashboard') }}" class="h-[36px] inline-flex items-center gap-1.5 px-4 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-[500] hover:bg-[#F5EFE6] transition">Dashboard</a>
                @elseif(Auth::guard('student')->check() && Auth::guard('student')->user()->hosting)
                  <a href="{{ route('mahasiswa.hosting.files') }}" class="h-[36px] inline-flex items-center gap-1.5 px-4 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-[500] hover:bg-[#F5EFE6] transition">File Manager</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline">@csrf<button class="h-[36px] px-4 rounded-full text-[13px] font-[450] text-[#6E6A64] hover:text-[#141210]">Keluar</button></form>
              @else
                <a href="{{ route('login') }}" class="h-[36px] inline-flex items-center gap-1.5 px-5 rounded-full bg-[#141210] text-white text-[13px] font-[500] tracking-[-0.01em] hover:bg-black transition">Masuk <i class="fa-solid fa-arrow-right text-[10px] opacity-60"></i></a>
              @endif
            @endif
        </div>

        <button @click="open=!open" :aria-expanded="open.toString()" aria-label="Menu" class="lg:hidden w-9 h-9 rounded-full border flex items-center justify-center transition shrink-0" :class="open ? 'bg-[#141210] border-[#141210] text-white' : 'bg-white border-[#E8DFD1] text-[#141210]'">
            <i x-show="!open" class="fa-solid fa-bars text-[13px]"></i>
            <i x-show="open" x-cloak class="fa-solid fa-xmark text-[13px]"></i>
        </button>
    </div>

    <!-- Mobile: side sheet -->
    <div x-show="open" x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak @click="open=false" class="lg:hidden fixed top-[64px] left-0 w-screen h-[calc(100dvh-64px)] bg-[#141210]/15 backdrop-blur-[2px]"></div>
    <div x-show="open"
         x-transition:enter="transition duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition duration-200 ease-[cubic-bezier(0.16,1,0.3,1)]"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         x-cloak
         class="lg:hidden fixed top-[64px] right-0 h-[calc(100dvh-64px)] w-[320px] max-w-[86vw] bg-[#FDF9F3] border-l border-[#E8DFD1] shadow-[-16px_0_40px_rgba(20,18,16,0.08)] flex flex-col">
        <div class="flex-1 overflow-y-auto px-5 py-6">
            <div class="space-y-1">
                <a @click="open=false" href="#roster" class="flex items-center justify-between h-[48px] px-4 rounded-xl text-[15px] font-[500] transition" :class="active==='roster' ? 'bg-white border border-[#E8DFD1] text-[#141210] shadow-sm' : 'text-[#141210]/70 hover:text-[#141210] hover:bg-white/60'"><span>Anggota</span><i class="fa-solid fa-chevron-right text-[11px] opacity-30"></i></a>
                <a @click="open=false" href="#projects" class="flex items-center justify-between h-[48px] px-4 rounded-xl text-[15px] font-[500] transition" :class="active==='projects' ? 'bg-white border border-[#E8DFD1] text-[#141210] shadow-sm' : 'text-[#141210]/70 hover:text-[#141210] hover:bg-white/60'"><span>Karya</span><i class="fa-solid fa-chevron-right text-[11px] opacity-30"></i></a>
                <a @click="open=false" href="#academic" class="flex items-center justify-between h-[48px] px-4 rounded-xl text-[15px] font-[500] transition" :class="active==='academic' ? 'bg-white border border-[#E8DFD1] text-[#141210] shadow-sm' : 'text-[#141210]/70 hover:text-[#141210] hover:bg-white/60'"><span>Akademik</span><i class="fa-solid fa-chevron-right text-[11px] opacity-30"></i></a>
                <a @click="open=false" href="#gallery" class="flex items-center justify-between h-[48px] px-4 rounded-xl text-[15px] font-[500] transition" :class="active==='gallery' ? 'bg-white border border-[#E8DFD1] text-[#141210] shadow-sm' : 'text-[#141210]/70 hover:text-[#141210] hover:bg-white/60'"><span>Kehidupan</span><i class="fa-solid fa-chevron-right text-[11px] opacity-30"></i></a>
            </div>
        </div>
        <div class="p-5 border-t border-[#E8DFD1] bg-white">
            @if(Route::has('login'))
              @if(Auth::guard('web')->check() || Auth::guard('student')->check())
                @if(Auth::guard('web')->check() && Auth::guard('web')->user()->role === 'admin')
                  <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center gap-2 h-[44px] rounded-full bg-[#141210] text-white text-[14px] font-[500]"><i class="fa-solid fa-gauge-high text-[12px]"></i> Dashboard</a>
                @elseif(Auth::guard('student')->check() && Auth::guard('student')->user()->hosting)
                  <a href="{{ route('mahasiswa.hosting.files') }}" class="flex items-center justify-center gap-2 h-[44px] rounded-full bg-[#141210] text-white text-[14px] font-[500]"><i class="fa-solid fa-folder text-[12px]"></i> File Manager</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="mt-2">@csrf<button class="w-full h-[44px] rounded-full border border-[#E8DFD1] bg-white text-[14px] font-[500]">Keluar</button></form>
              @else
                <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 h-[44px] rounded-full bg-[#141210] text-white text-[14px] font-[500] hover:bg-black transition">Masuk Portal <i class="fa-solid fa-arrow-right text-[11px] opacity-60"></i></a>
                <p class="text-center font-mono text-[11px] tracking-[0.02em] text-[#7A7670] mt-2">Polindra · D4 RPL 4B</p>
              @endif
            @endif
        </div>
    </div>
</nav>
