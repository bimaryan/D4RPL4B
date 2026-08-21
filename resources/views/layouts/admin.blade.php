<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — D4 RPL 4B</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Instrument+Sans:wght@400;500;600&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    @endif
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root{ --paper:#FDF9F3; --paper-2:#F5EFE6; --ink:#141210; --muted:#7A7670; --line:#E8DFD1; --accent:#E84E0F; --sidebar:#11100F; }
        body{ font-family:'Inter','Instrument Sans',system-ui,sans-serif; background:#F6F5F1; color:var(--ink); -webkit-font-smoothing:antialiased; }
        .font-display{ font-family:'Fraunces',serif; } .font-mono{ font-family:'JetBrains Mono',monospace; }
        .form-input{ width:100%; background:#fff; border:1px solid #E5E0D6; border-radius:10px; padding:9px 12px; font-size:13.5px; transition:all .15s; }
        .form-input:focus{ outline:none; border-color:#11100F; box-shadow:0 0 0 3px rgba(17,16,15,.08); }
        [x-cloak]{ display:none !important; }
        /* sidebar active */
        .nav-active{ background:#1E1D1B; color:#fff; }
        .nav-idle{ color:#A8A29E; }
        .nav-idle:hover{ background:#1E1D1B; color:#fff; }
    </style>
</head>
<body class="min-h-screen" x-data="{ sidebarOpen: false, toasts: [], confirmOpen:false, confirmForm:null, confirmTitle:'Hapus data?', confirmMessage:'Tindakan ini tidak bisa dibatalkan.', showToast(msg, type='success'){ const id=Date.now(); this.toasts.push({id, msg, type}); setTimeout(()=>{ this.toasts=this.toasts.filter(t=>t.id!==id)}, 3500); }, openConfirm(form, title, msg){ this.confirmForm=form; this.confirmTitle=title; this.confirmMessage=msg; this.confirmOpen=true; }, doConfirm(){ if(this.confirmForm) this.confirmForm.submit(); this.confirmOpen=false; } }" x-init="
        @if(session('success')) showToast(@js(session('success')), 'success'); @endif
        @if(session('error')) showToast(@js(session('error')), 'error'); @endif
        @if($errors->any()) showToast(@js($errors->first()), 'error'); @endif
    ">
    <!-- Mobile overlay -->
    <div x-show="sidebarOpen" x-cloak x-transition.opacity class="fixed inset-0 bg-black/40 z-40 lg:hidden" @click="sidebarOpen=false"></div>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside x-cloak class="fixed inset-y-0 left-0 z-50 w-[272px] bg-[#11100F] text-white flex flex-col -translate-x-full lg:translate-x-0 transition-transform duration-200" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
            <!-- brand -->
            <div class="h-[64px] flex items-center gap-3 px-6 border-b border-white/10 shrink-0">
                <div class="w-8 h-8 rounded-lg bg-white text-[#11100F] flex items-center justify-center font-mono text-[11px] font-bold">4B</div>
                <div class="leading-none">
                    <div class="font-semibold text-[14px] tracking-tight">D4 RPL 4B</div>
                    <div class="text-[11px] tracking-[0.08em] uppercase text-white/50">Admin Panel</div>
                </div>
                <button @click="sidebarOpen=false" class="lg:hidden ml-auto w-8 h-8 rounded-full bg-white/10 flex items-center justify-center"><i class="fa-solid fa-xmark text-[12px]"></i></button>
            </div>

            <!-- nav -->
            <nav class="flex-1 px-3 py-5 space-y-6 overflow-y-auto">
                <div>
                    <div class="px-3 mb-2 text-[11px] font-semibold tracking-[0.12em] uppercase text-white/30">Menu</div>
                    <div class="space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition {{ request()->routeIs('admin.dashboard') ? 'nav-active' : 'nav-idle' }}">
                            <span class="w-7 h-7 rounded-md flex items-center justify-center text-[13px] {{ request()->routeIs('admin.dashboard') ? 'bg-white text-[#11100F]' : 'bg-white/10' }}"><i class="fa-solid fa-gauge-high"></i></span> Dashboard
                        </a>
                        <a href="{{ route('students.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition {{ request()->routeIs('students.*') ? 'nav-active' : 'nav-idle' }}">
                            <span class="w-7 h-7 rounded-md flex items-center justify-center text-[13px] {{ request()->routeIs('students.*') ? 'bg-white text-[#11100F]' : 'bg-white/10' }}"><i class="fa-solid fa-users"></i></span> Mahasiswa <span class="ml-auto text-[11px] bg-white/10 px-1.5 py-0.5 rounded font-mono">{{ \App\Models\Student::count() }}</span>
                        </a>
                        <a href="{{ route('projects.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition {{ request()->routeIs('projects.*') ? 'nav-active' : 'nav-idle' }}">
                            <span class="w-7 h-7 rounded-md flex items-center justify-center text-[13px] {{ request()->routeIs('projects.*') ? 'bg-white text-[#11100F]' : 'bg-white/10' }}"><i class="fa-solid fa-layer-group"></i></span> Karya / Proyek
                        </a>
                        <a href="{{ route('announcements.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition {{ request()->routeIs('announcements.*') ? 'nav-active' : 'nav-idle' }}">
                            <span class="w-7 h-7 rounded-md flex items-center justify-center text-[13px] {{ request()->routeIs('announcements.*') ? 'bg-white text-[#11100F]' : 'bg-white/10' }}"><i class="fa-solid fa-bullhorn"></i></span> Pengumuman
                        </a>
                        <a href="{{ route('galleries.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition {{ request()->routeIs('galleries.*') ? 'nav-active' : 'nav-idle' }}">
                            <span class="w-7 h-7 rounded-md flex items-center justify-center text-[13px] {{ request()->routeIs('galleries.*') ? 'bg-white text-[#11100F]' : 'bg-white/10' }}"><i class="fa-solid fa-images"></i></span> Gallery <span class="ml-auto text-[11px] bg-white/10 px-1.5 py-0.5 rounded font-mono">{{ \App\Models\Gallery::count() }}</span>
                        </a>
                        <a href="{{ route('schedules.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition {{ request()->routeIs('schedules.*') ? 'nav-active' : 'nav-idle' }}">
                            <span class="w-7 h-7 rounded-md flex items-center justify-center text-[13px] {{ request()->routeIs('schedules.*') ? 'bg-white text-[#11100F]' : 'bg-white/10' }}"><i class="fa-regular fa-calendar"></i></span> Jadwal Kuliah <span class="ml-auto text-[11px] bg-white/10 px-1.5 py-0.5 rounded font-mono">{{ \App\Models\Schedule::count() }}</span>
                        </a>
                        <a href="{{ route('hero.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition {{ request()->routeIs('hero.*') ? 'nav-active' : 'nav-idle' }}">
                            <span class="w-7 h-7 rounded-md flex items-center justify-center text-[13px] {{ request()->routeIs('hero.*') ? 'bg-white text-[#11100F]' : 'bg-white/10' }}"><i class="fa-solid fa-panorama"></i></span> Hero Image
                        </a>
                    </div>
                </div>

                <div>
                    <div class="px-3 mb-2 text-[11px] font-semibold tracking-[0.12em] uppercase text-white/30">Umum</div>
                    <div class="space-y-1">
                        <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium nav-idle">
                            <span class="w-7 h-7 rounded-md bg-white/10 flex items-center justify-center text-[12px]"><i class="fa-solid fa-arrow-up-right-from-square"></i></span> Lihat Landing
                        </a>
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium nav-idle opacity-60">
                            <span class="w-7 h-7 rounded-md bg-white/10 flex items-center justify-center text-[12px]"><i class="fa-solid fa-gear"></i></span> Pengaturan
                        </a>
                    </div>
                </div>
            </nav>

            <!-- user -->
            <div class="p-4 border-t border-white/10">
                <div class="flex items-center gap-3 bg-white/[0.06] border border-white/10 rounded-xl p-3">
                    <img src="https://api.dicebear.com/7.x/initials/svg?seed=Admin&backgroundColor=11100F" class="w-9 h-9 rounded-full border border-white/10 bg-white" alt="admin">
                    <div class="min-w-0 flex-1">
                        <div class="text-[13px] font-medium leading-none truncate">{{ Auth::user()->name ?? 'Admin' }}</div>
                        <div class="text-[11px] text-white/50 truncate">{{ Auth::user()->email ?? 'admin@polindra.ac.id' }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-8 h-8 rounded-full bg-white text-[#11100F] flex items-center justify-center hover:bg-white/90 transition" title="Keluar"><i class="fa-solid fa-right-from-bracket text-[11px]"></i></button>
                    </form>
                </div>
                <div class="text-center font-mono text-[10px] tracking-wide text-white/25 mt-3">© {{ date('Y') }} Polindra • v1.2</div>
            </div>
        </aside>

        <!-- Main -->
        <div class="flex-1 lg:ml-[272px] flex flex-col min-w-0">
            <!-- Topbar -->
            <header class="h-[64px] bg-white border-b border-[#E8DFD1] flex items-center gap-4 px-4 lg:px-8 sticky top-0 z-30">
                <button @click="sidebarOpen=true" class="lg:hidden w-9 h-9 rounded-full border border-[#E8DFD1] flex items-center justify-center"><i class="fa-solid fa-bars text-[13px]"></i></button>
                <div class="hidden lg:flex items-center gap-2 text-[13px] text-[#7A7670]">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-[#11100F]">Home</a>
                    <span class="opacity-30">/</span>
                    <span class="font-medium text-[#11100F]">@yield('breadcrumb', 'Dashboard')</span>
                </div>
                <div class="ml-auto flex items-center gap-2">
                    <div class="hidden sm:flex items-center gap-2 bg-[#F6F5F1] border border-[#E8DFD1] rounded-full pl-3 pr-1 py-1">
                        <i class="fa-solid fa-magnifying-glass text-[11px] text-[#7A7670]"></i>
                        <input placeholder="Cari mahasiswa, proyek..." class="bg-transparent text-[13px] outline-none w-[200px] placeholder:text-[#A8A29E]">
                        <span class="font-mono text-[10px] bg-white border border-[#E8DFD1] rounded-full px-2 py-1">⌘ K</span>
                    </div>
                    <button class="w-9 h-9 rounded-full border border-[#E8DFD1] bg-white flex items-center justify-center text-[#7A7670] hover:text-[#11100F]"><i class="fa-regular fa-bell text-[14px]"></i></button>
                    <a href="{{ url('/') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black transition"><i class="fa-solid fa-eye text-[11px]"></i> Preview</a>
                </div>
            </header>

            <!-- Toast Container -->
            <div class="fixed top-4 right-4 z-[80] flex flex-col gap-2 pointer-events-none">
                <template x-for="t in toasts" :key="t.id">
                    <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2 translate-x-4" x-transition:enter-end="opacity-100 translate-y-0 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 translate-x-4"
                         class="pointer-events-auto min-w-[320px] max-w-[420px] bg-white border rounded-xl shadow-[0_12px_32px_rgba(0,0,0,0.12)] p-4 flex gap-3" :class="t.type==='error' ? 'border-red-200' : 'border-emerald-200'">
                        <span class="w-8 h-8 rounded-full flex items-center justify-center shrink-0" :class="t.type==='error' ? 'bg-red-500 text-white' : 'bg-emerald-500 text-white'"><i :class="t.type==='error' ? 'fa-solid fa-triangle-exclamation text-[12px]' : 'fa-solid fa-check text-[12px]'"></i></span>
                        <div class="flex-1 min-w-0">
                            <div class="text-[13px] font-[600] leading-none" :class="t.type==='error' ? 'text-red-900' : 'text-emerald-900'" x-text="t.type==='error' ? 'Gagal' : 'Berhasil'"></div>
                            <div class="text-[12.5px] leading-snug mt-1" :class="t.type==='error' ? 'text-red-700' : 'text-[#4A4743]'" x-text="t.msg"></div>
                        </div>
                        <button @click="toasts=toasts.filter(x=>x.id!==t.id)" class="w-7 h-7 rounded-full hover:bg-[#F6F5F1] flex items-center justify-center shrink-0 text-[#7A7670]"><i class="fa-solid fa-xmark text-[11px]"></i></button>
                    </div>
                </template>
            </div>

            <!-- Confirm Modal -->
            <div x-show="confirmOpen" x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak class="fixed inset-0 z-[90] flex items-center justify-center p-4">
                <div @click="confirmOpen=false" class="absolute inset-0 bg-[#141210]/40 backdrop-blur-sm"></div>
                <div x-transition:enter="transition duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     class="relative bg-white rounded-[16px] border border-[#E8DFD1] shadow-[0_20px_48px_rgba(0,0,0,0.16)] w-full max-w-[420px] overflow-hidden">
                    <div class="p-6">
                        <div class="w-10 h-10 rounded-full bg-red-50 border border-red-200 flex items-center justify-center text-red-600 mb-3"><i class="fa-solid fa-trash text-[14px]"></i></div>
                        <h3 class="font-semibold text-[16px] tracking-[-0.01em]" x-text="confirmTitle"></h3>
                        <p class="text-[13px] leading-[1.5] text-[#7A7670] mt-1.5" x-text="confirmMessage"></p>
                    </div>
                    <div class="flex items-center justify-end gap-2 px-6 py-4 bg-[#FCFBF9] border-t border-[#E8DFD1]">
                        <button @click="confirmOpen=false" class="px-5 py-2 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-[500] hover:bg-[#F6F5F1]">Batal</button>
                        <button @click="doConfirm()" class="px-6 py-2 rounded-full bg-red-600 text-white text-[13px] font-[500] hover:bg-red-700 inline-flex items-center gap-1.5"><i class="fa-solid fa-trash text-[11px]"></i> Ya, Hapus</button>
                    </div>
                </div>
            </div>

            <script>
                window.confirmDelete = function(form, title, message){
                    if(event) event.preventDefault();
                    try {
                        const data = Alpine.$data(document.body);
                        data.openConfirm(form, title || 'Hapus data?', message || 'Tindakan ini tidak bisa dibatalkan.');
                    } catch(e){
                        if(confirm(message)) form.submit();
                    }
                    return false;
                }
            </script>

            <!-- Content -->
            <main class="flex-1 bg-[#F6F5F1] p-4 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
