@extends('layouts.admin')
@section('title', 'Web Terminal - ' . $hosting->student->name)
@section('breadcrumb', 'Hosting / ' . $hosting->student->nim . ' / Terminal')
@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('hostings.show', $hosting->hash_id) }}" class="w-9 h-9 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center hover:bg-[#F6F5F1]"><i class="fa-solid fa-arrow-left text-[12px]"></i></a>
    <div class="w-10 h-10 rounded-full bg-[#11100F] flex items-center justify-center text-white"><i class="fa-solid fa-terminal text-[18px]"></i></div>
    <div class="flex-1 min-w-0">
        <h1 class="font-semibold text-[18px] leading-none">Web Terminal</h1>
        <p class="font-mono text-[11px] text-[#7A7670]">{{ $hosting->path }}</p>
    </div>
</div>

<div class="bg-[#11100F] rounded-xl overflow-hidden shadow-xl border border-white/10 flex flex-col h-[600px]">
    <div class="px-4 py-3 bg-[#1A1A1A] border-b border-white/10 flex items-center gap-2">
        <div class="flex gap-1.5">
            <div class="w-3 h-3 rounded-full bg-red-500"></div>
            <div class="w-3 h-3 rounded-full bg-amber-500"></div>
            <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
        </div>
        <div class="text-white/50 text-[11px] font-mono ml-3">Terminal ({{ $hosting->student->nim }}) - Secure Web SSH</div>
    </div>
    
    <div class="flex-1 p-4 overflow-y-auto font-mono text-[13px] text-gray-300 flex flex-col gap-2" id="terminal-output">
        <div class="text-emerald-400">Welcome to D4RPL4B Web Terminal.</div>
        <div class="text-white/50">Current directory is isolated to your hosting folder.</div>
        <div>$ php -v</div>
        <div class="text-emerald-300">PHP 8.x (cli)</div>
    </div>
    
    <div class="p-3 bg-[#1A1A1A] border-t border-white/10 flex items-center gap-2">
        <span class="text-emerald-500 font-mono text-[13px] font-bold">$</span>
        <input type="text" id="terminal-input" class="flex-1 bg-transparent border-none outline-none text-white font-mono text-[13px] placeholder:text-white/20 p-0 focus:ring-0" placeholder="Ketik perintah (contoh: ls, php -v, composer install)..." autocomplete="off" autofocus>
        <button id="terminal-btn" class="w-8 h-8 rounded bg-white/10 text-white flex items-center justify-center hover:bg-white/20 transition"><i class="fa-solid fa-level-down-alt rotate-90 text-[12px]"></i></button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('terminal-input');
        const output = document.getElementById('terminal-output');
        const btn = document.getElementById('terminal-btn');
        let isProcessing = false;

        function appendLine(text, isCommand = false) {
            const div = document.createElement('div');
            if (isCommand) {
                div.innerHTML = `<span class="text-emerald-500 font-bold">$</span> <span class="text-white">${text}</span>`;
            } else {
                div.textContent = text;
                if(text === '') div.innerHTML = '&nbsp;';
            }
            output.appendChild(div);
            output.scrollTop = output.scrollHeight;
        }

        async function executeCommand() {
            if (isProcessing) return;
            const cmd = input.value.trim();
            if (!cmd) return;

            input.value = '';
            appendLine(cmd, true);
            isProcessing = true;
            input.disabled = true;

            try {
                const response = await fetch("{{ route('hostings.terminal.execute', $hosting->hash_id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ command: cmd })
                });
                
                const data = await response.json();
                if (data.output) {
                    // split by newline and append
                    const lines = data.output.split('\n');
                    lines.forEach(l => appendLine(l));
                }
            } catch (error) {
                appendLine('Error executing command: ' + error.message);
                output.lastChild.classList.add('text-red-400');
            }

            isProcessing = false;
            input.disabled = false;
            input.focus();
        }

        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') executeCommand();
        });
        
        btn.addEventListener('click', executeCommand);
    });
</script>
@endsection
