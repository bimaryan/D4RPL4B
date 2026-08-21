@props(['announcements', 'schedules' => collect()])

<section id="academic" data-motion="academic" class="py-16 lg:py-24 border-t border-[#E8DFD1] bg-[#F5EFE6]/50 scroll-mt-[72px]">
    <div class="max-w-[1280px] mx-auto px-6 lg:px-8">
        <div data-motion="section-header" class="flex items-center gap-3 mb-8">
            <h2 class="font-display text-[30px] sm:text-[38px] font-semibold tracking-tight">Papan Akademik</h2>
            <span class="hidden sm:inline-flex font-mono text-[11px] tracking-wide uppercase bg-white border border-[#E8DFD1] rounded-full px-3 py-1 text-[#7A7670]">Polindra • TI — Jadwal & Pengumuman</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            <div data-motion="academic-schedule" class="lg:col-span-8 bg-white border border-[#E8DFD1] rounded-[18px] overflow-hidden will-change-transform">
                <div class="flex items-center justify-between px-6 py-4 border-b border-[#E8DFD1]">
                    <h3 class="font-medium text-[14px] flex items-center gap-2"><span class="w-7 h-7 rounded-full bg-[#FDF9F3] border border-[#E8DFD1] flex items-center justify-center"><i class="fa-regular fa-calendar text-[12px]"></i></span> Jadwal Kuliah — Semester 8</h3>
                    <span class="font-mono text-[11px] text-[#7A7670]"><i class="fa-regular fa-clock mr-1"></i> Genap 2025/2026</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-[13px] whitespace-nowrap">
                        <thead class="font-mono text-[11px] tracking-[0.08em] uppercase text-[#7A7670] bg-[#FDF9F3] border-b border-[#E8DFD1]">
                            <tr>
                                <th class="py-3 px-6 font-medium">Hari & Jam</th>
                                <th class="py-3 px-4 font-medium">Mata Kuliah</th>
                                <th class="py-3 px-4 font-medium hidden md:table-cell">Dosen</th>
                                <th class="py-3 px-6 font-medium text-right">Ruang</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E8DFD1]/70">
                            @forelse($schedules as $jadwal)
                            <tr class="hover:bg-[#FDF9F3]/60 transition">
                                <td class="py-4 px-6"><div class="font-medium">{{ $jadwal->day }}</div><div class="font-mono text-[12px] text-[#7A7670]">{{ $jadwal->time_start }} – {{ $jadwal->time_end }}</div></td>
                                <td class="py-4 px-4 font-medium">{{ $jadwal->course }}<div class="md:hidden font-normal text-[12px] text-[#7A7670]">{{ $jadwal->lecturer }}</div></td>
                                <td class="py-4 px-4 text-[#4A4743] hidden md:table-cell">{{ $jadwal->lecturer ?? '—' }}</td>
                                <td class="py-4 px-6 text-right"><span class="inline-flex font-mono text-[11px] border border-[#E8DFD1] rounded-full px-2.5 py-1 bg-[#FDF9F3]">{{ $jadwal->room ?? '—' }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-10 text-center">
                                    <div class="w-10 h-10 rounded-full bg-[#FDF9F3] border border-[#E8DFD1] flex items-center justify-center mx-auto mb-2"><i class="fa-regular fa-calendar-xmark text-[#7A7670]"></i></div>
                                    <div class="text-[13px] text-[#7A7670]">Belum ada jadwal. Tambah via admin panel.</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-3 bg-[#FDF9F3] border-t border-[#E8DFD1] font-mono text-[11px] text-[#7A7670] flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Jadwal dapat berubah — cek pengumuman di samping.
                </div>
            </div>

            <div data-motion="academic-announcements" class="lg:col-span-4 bg-white border border-[#E8DFD1] rounded-[18px] p-6 flex flex-col will-change-transform">
                <h3 class="font-medium text-[14px] flex items-center gap-2"><i class="fa-solid fa-bullhorn text-[#E84E0F] text-[13px]"></i> Pengumuman <span class="ml-auto font-mono text-[11px] bg-[#E84E0F] text-white rounded-full px-2 py-0.5">{{ $announcements->count() }}</span></h3>
                <div data-motion="announcements-list" class="mt-4 space-y-3 overflow-y-auto max-h-[420px] pr-1 custom-scroll">
                    @forelse($announcements as $a)
                    <div class="motion-announcement rounded-[14px] border p-4 {{ $a->category == 'Urgent Deadline' ? 'bg-[#FFF1EC] border-[#F2C0AA]' : 'bg-[#FDF9F3] border-[#E8DFD1]' }}">
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-[10.5px] tracking-wide uppercase font-medium {{ $a->category == 'Urgent Deadline' ? 'text-[#E84E0F]' : 'text-[#7A7670]' }}">{{ $a->category }}</span>
                            <span class="font-mono text-[11px] text-[#7A7670]">{{ $a->event_date ? \Carbon\Carbon::parse($a->event_date)->format('d M') : 'TBA' }}</span>
                        </div>
                        <div class="font-medium text-[13.5px] leading-tight mt-1.5">{{ $a->title }}</div>
                        <div class="text-[12.5px] leading-relaxed text-[#6E6A64] mt-1 line-clamp-3">{{ $a->content }}</div>
                    </div>
                    @empty
                    <div class="rounded-[14px] border border-dashed border-[#E8DFD1] p-8 text-center">
                        <div class="text-[13px] text-[#7A7670]">Belum ada pengumuman.</div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.custom-scroll::-webkit-scrollbar { width: 4px; }
.custom-scroll::-webkit-scrollbar-thumb { background: #D6CFC2; border-radius: 999px; }
</style>
