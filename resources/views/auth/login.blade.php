<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — D4 RPL 4B</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Instrument+Sans:wght@400;500;600&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    @endif
    <style>
        :root{ --paper:#FDF9F3; --ink:#141210; --line:#E8DFD1; }
        body{ font-family:'Instrument Sans',sans-serif; background:var(--paper); color:var(--ink); }
        .font-display{ font-family:'Fraunces',serif; }
        .font-mono{ font-family:'JetBrains Mono',monospace; }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <div class="flex-1 flex">
        <!-- Left brand -->
        <div class="hidden lg:flex w-[46%] bg-[#141210] text-white p-10 flex-col justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-[9px] bg-white text-[#141210] flex items-center justify-center font-mono text-[11px] font-medium">4B</div>
                    <div class="font-display font-semibold">D4 RPL 4B</div>
                </div>
                <div class="mt-16 max-w-[420px]">
                    <div class="font-display text-[42px] leading-[0.95] tracking-tight">Kelola<br><span class="italic font-normal text-[#FF8A5B]">kelasmu</span> dengan rapi.</div>
                    <p class="text-white/60 text-[14px] leading-relaxed mt-4">Portal admin untuk 30 mahasiswa Polindra. Update roster, publish karya, dan umumkan jadwal — semua sinkron ke landing page.</p>
                </div>
            </div>
            <div class="font-mono text-[11px] text-white/40">© {{ date('Y') }} Polindra • Warm Editorial</div>
        </div>

        <!-- Right form -->
        <div class="flex-1 flex items-center justify-center p-6 lg:p-10 bg-[#FDF9F3]">
            <div class="w-full max-w-[420px] bg-white border border-[#E8DFD1] rounded-[20px] p-8 shadow-[0_12px_40px_rgba(20,18,16,0.06)]">
                <div class="lg:hidden flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-[9px] bg-[#141210] text-white flex items-center justify-center font-mono text-[11px]">4B</div>
                    <div class="font-display font-semibold text-[15px]">D4 RPL 4B</div>
                </div>
                <h1 class="font-display text-[24px] font-semibold tracking-tight">Masuk Portal</h1>
                <p class="text-[13px] text-[#7A7670] mt-1">Mahasiswa: NIM sebagai username & password. Admin: pakai NIM atau email.</p>

                @if ($errors->any())
                    <div class="mt-5 rounded-[12px] bg-red-50 border border-red-200 text-red-700 text-[13px] px-4 py-3 flex items-start gap-2"><i class="fa-solid fa-circle-exclamation mt-0.5"></i> <span>{{ $errors->first() }}</span></div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block font-mono text-[11px] tracking-wide uppercase text-[#7A7670] mb-1.5">NIM <span class="normal-case tracking-normal text-[#A8A29E]">/ Email admin</span></label>
                        <input type="text" name="nim" value="{{ old('nim') }}" required autofocus placeholder="Contoh: 23001xxx"
                            class="w-full bg-white border border-[#E8DFD1] rounded-full px-4 py-3 text-[14px] focus:outline-none focus:border-[#141210] focus:ring-4 focus:ring-black/5 transition">
                        <div class="text-[11px] text-[#7A7670] mt-1.5 flex items-center gap-1.5"><i class="fa-solid fa-circle-info text-[10px]"></i> Mahasiswa: password = NIM yang sama</div>
                    </div>
                    <div>
                        <label class="block font-mono text-[11px] tracking-wide uppercase text-[#7A7670] mb-1.5">Password</label>
                        <input type="password" name="password" required placeholder="Masukkan NIM sebagai password"
                            class="w-full bg-white border border-[#E8DFD1] rounded-full px-4 py-3 text-[14px] focus:outline-none focus:border-[#141210] focus:ring-4 focus:ring-black/5 transition">
                    </div>
                    <label class="flex items-center gap-2 py-1">
                        <input type="checkbox" name="remember" class="rounded border-[#E8DFD1]"> <span class="text-[13px] text-[#7A7670]">Ingat saya</span>
                    </label>
                    <button type="submit" class="w-full py-3 rounded-full bg-[#141210] text-white font-medium text-[14px] hover:bg-black transition">Masuk →</button>
                </form>
                <a href="{{ url('/') }}" class="block text-center mt-6 text-[13px] font-medium border border-[#E8DFD1] rounded-full py-2.5 hover:bg-[#FDF9F3] transition">← Kembali ke Landing</a>
            </div>
        </div>
    </div>
</body>
</html>
