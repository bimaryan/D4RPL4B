<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>D4 RPL 4B — Politeknik Negeri Indramayu</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,ital,wght@9..144,0,400;9..144,0,500;9..144,0,600;9..144,0,700;9..144,1,600&family=Instrument+Sans:wght@400;500;600&family=JetBrains+Mono:wght@400;500&family=Newsreader:opsz,wght@6..72,400;6..72,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    @endif

    <script>document.documentElement.classList.add('motion-ready');</script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        :root {
            --paper: #FDF9F3;
            --paper-2: #F5EFE6;
            --ink: #141210;
            --muted: #7A7670;
            --line: #E8DFD1;
            --accent: #E84E0F;
            --accent-hover: #C9430D;
        }
        * { font-variant-ligatures: common-ligatures; }
        html { scroll-padding-top: 72px; }
        body { 
            font-family: 'Instrument Sans', system-ui, -apple-system, sans-serif;
            background: var(--paper);
            color: var(--ink);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            font-feature-settings: "ss01" 1, "ss02" 1;
            text-rendering: optimizeLegibility;
        }
        .font-display { font-family: 'Fraunces', serif; }
        .font-serif { font-family: 'Newsreader', serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        /* paper texture - subtle grain */
        .paper-texture {
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: 0.035;
            z-index: -1;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }

        /* thin hairline */
        .hairline { border-color: var(--line); }
        .bg-paper { background: var(--paper); }
        .bg-ink { background: var(--ink); }
        .text-ink { color: var(--ink); }
        .text-muted { color: var(--muted); }
        .text-accent { color: var(--accent); }
        .bg-accent { background: var(--accent); }
        .border-line { border-color: var(--line); }

        /* motion base - framer motion initial states only when JS ready */
        [data-motion] { will-change: transform, opacity; }
        .motion-ready [data-motion="hero-badge"],
        .motion-ready [data-motion="hero-title"] span,
        .motion-ready [data-motion="hero-desc"],
        .motion-ready [data-motion="hero-cta"],
        .motion-ready [data-motion="hero-visual"],
        .motion-ready [data-motion="hero-stats"] > div,
        .motion-ready [data-motion="section-header"],
        .motion-ready [data-motion="roster-badge"],
        .motion-ready .motion-roster-card,
        .motion-ready .motion-project-card,
        .motion-ready [data-motion="academic-schedule"],
        .motion-ready [data-motion="academic-announcements"],
        .motion-ready .motion-announcement,
        .motion-ready .motion-gallery-item {
            opacity: 0;
        }
        @media (prefers-reduced-motion: reduce) {
            .motion-ready [data-motion="hero-badge"],
            .motion-ready [data-motion="hero-title"] span,
            .motion-ready [data-motion="hero-desc"],
            .motion-ready [data-motion="hero-cta"],
            .motion-ready [data-motion="hero-visual"],
            .motion-ready [data-motion="hero-stats"] > div,
            .motion-ready [data-motion="section-header"],
            .motion-ready [data-motion="roster-badge"],
            .motion-ready .motion-roster-card,
            .motion-ready .motion-project-card,
            .motion-ready [data-motion="academic-schedule"],
            .motion-ready [data-motion="academic-announcements"],
            .motion-ready .motion-announcement,
            .motion-ready .motion-gallery-item { opacity: 1; transform: none !important; }
        }

        ::selection { background: var(--ink); color: white; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--paper); }
        ::-webkit-scrollbar-thumb { background: #D6CFC2; border-radius: 999px; }
    </style>
</head>
<body class="antialiased">
    <div class="paper-texture"></div>
    {{ $slot }}
</body>
</html>
