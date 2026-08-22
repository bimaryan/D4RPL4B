@extends('layouts.admin')
@section('title', 'File Manager - ' . $hosting->student->name)
@section('breadcrumb', 'Hosting / File Manager')




@section('content')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('fileManager', () => ({
        path: @js($rel),
        showNewFolder: false, newFolderName: '',
        showNewFile: false, newFileName: '', newFileContent: '',
        showRename: false, renamePath: '', renameNew: '',
        showEditor: false, editPath: '', editContent: '', editName: '',
        showUpload: false,
        breadcrumbs: @js($breadcrumbs),
        selectedFiles: [],
        selectAll: false,
        searchQuery: '',
        allItems: @js($items),
        clipboard: JSON.parse(sessionStorage.getItem('fm_clipboard') || '{"mode":"","files":[]}'),
        navigate(p) { window.location = '?path=' + encodeURIComponent(p); },
        get filteredItems() {
            if (!this.searchQuery.trim()) return this.allItems;
            const q = this.searchQuery.toLowerCase();
            return this.allItems.filter(it => it.name.toLowerCase().includes(q));
        },
        get isIndeterminate() {
            return this.selectedFiles.length > 0 && this.selectedFiles.length < this.filteredItems.length;
        },
        get isAllSelected() {
            return this.filteredItems.length > 0 && this.selectedFiles.length === this.filteredItems.length;
        },
        toggleSelectAll() {
            if (this.isAllSelected) {
                this.selectedFiles = [];
                this.selectAll = false;
            } else {
                this.selectedFiles = this.filteredItems.map(it => it.path);
                this.selectAll = true;
            }
        },
        updateCheckboxState() {
            const cb = document.getElementById('cb-select-all');
            if (!cb) return;
            cb.indeterminate = this.isIndeterminate;
            cb.checked = this.isAllSelected;
        },
        copySelected() {
            this.clipboard = { mode: 'copy', files: this.selectedFiles };
            sessionStorage.setItem('fm_clipboard', JSON.stringify(this.clipboard));
            this.selectedFiles = [];
            this.selectAll = false;
        },
        cutSelected() {
            this.clipboard = { mode: 'cut', files: this.selectedFiles };
            sessionStorage.setItem('fm_clipboard', JSON.stringify(this.clipboard));
            this.selectedFiles = [];
            this.selectAll = false;
        },
        clearClipboard() {
            this.clipboard = { mode: '', files: [] };
            sessionStorage.removeItem('fm_clipboard');
        }
    }));
});
</script>
<div id="fm-root" x-data="fileManager()" class="w-full relative">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 mb-4">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ $r_back }}" class="w-9 h-9 shrink-0 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center hover:bg-[#F6F5F1]"><i class="fa-solid fa-arrow-left text-[12px]"></i></a>
            <div class="min-w-0">
                <h1 class="font-semibold text-[16px] leading-none flex items-center gap-2"><i class="fa-solid fa-folder-open text-[#2563EB]"></i> File Manager</h1>
                <p class="font-mono text-[11px] text-[#7A7670] truncate">{{ $hosting->student->nim }} • {{ $usage['human'] }} / {{ $hosting->quota_mb }} MB</p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ $hosting->domain_url }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 h-[36px] px-4 rounded-full bg-emerald-500 text-white text-[12px] font-medium hover:bg-emerald-600"><i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> Buka Site</a>
            <span class="hidden sm:inline-flex items-center gap-1.5 text-[11px] font-mono bg-white border border-[#E8DFD1] rounded-full px-3 h-[36px]"><span class="w-2 h-2 rounded-full {{ $hosting->status==='active'?'bg-emerald-500':'bg-red-500' }}"></span> {{ $usage['files'] }} files</span>

            <!-- Action Bar for selected items -->
            <div x-show="selectedFiles.length > 0" x-cloak class="flex items-center gap-1.5 bg-[#F6F5F1] px-3 py-2 rounded-full border border-[#E8DFD1]">
                <span class="text-[12px] font-medium mr-1"><span x-text="selectedFiles.length"></span> dipilih</span>
                <button @click="cutSelected()" class="w-7 h-7 rounded-full bg-white border border-[#E8DFD1] hover:bg-[#11100F] hover:text-white flex items-center justify-center text-[11px]" title="Cut">
                    <i class="fa-solid fa-scissors"></i>
                </button>
                <button @click="copySelected()" class="w-7 h-7 rounded-full bg-white border border-[#E8DFD1] hover:bg-[#11100F] hover:text-white flex items-center justify-center text-[11px]" title="Copy">
                    <i class="fa-solid fa-copy"></i>
                </button>
                <form action="{{ $r_bulk_delete }}" method="POST" onsubmit="return confirmDelete(this, 'Hapus Massal?', 'Yakin ingin menghapus ' + selectedFiles.length + ' item secara permanen?')">
                    @csrf
                    <template x-for="p in selectedFiles" :key="p">
                        <input type="hidden" name="paths[]" :value="p">
                    </template>
                    <button type="submit" class="w-7 h-7 rounded-full bg-red-50 border border-red-200 text-red-600 hover:bg-red-600 hover:text-white flex items-center justify-center text-[11px]" title="Delete">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>

            <!-- Paste Action -->
            <div x-show="clipboard.files.length > 0" x-cloak class="flex items-center gap-2">
                <form action="{{ $r_paste }}" method="POST">
                    @csrf
                    <input type="hidden" name="mode" :value="clipboard.mode">
                    <input type="hidden" name="destination" :value="path">
                    <template x-for="p in clipboard.files" :key="p">
                        <input type="hidden" name="files[]" :value="p">
                    </template>
                    <button type="submit" @click="setTimeout(()=>clearClipboard(), 100)" class="h-9 px-4 rounded-full bg-[#11100F] text-white text-[12px] font-medium hover:bg-black shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-paste"></i>
                        <span class="hidden sm:inline">Paste</span> (<span x-text="clipboard.files.length"></span>)
                    </button>
                </form>
                <button @click="clearClipboard()" class="w-9 h-9 rounded-full border border-[#E8DFD1] bg-white hover:bg-red-50 hover:text-red-600 flex items-center justify-center text-[12px]" title="Batal Paste">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="h-8 w-px bg-[#E8DFD1] mx-0.5 hidden sm:block"></div>

            <!-- Folder button: icon only on mobile -->
            <button @click="showNewFolder=true" class="w-9 h-9 sm:w-auto sm:h-auto sm:px-4 sm:py-2.5 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-medium hover:bg-[#F6F5F1] shadow-sm flex items-center justify-center gap-2" title="Buat Folder">
                <i class="fa-solid fa-folder-plus text-[#7A7670]"></i>
                <span class="hidden sm:inline">Folder</span>
            </button>
            <!-- File button: icon only on mobile -->
            <button @click="showNewFile=true" class="w-9 h-9 sm:w-auto sm:h-auto sm:px-4 sm:py-2.5 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-medium hover:bg-[#F6F5F1] shadow-sm flex items-center justify-center gap-2" title="Buat File">
                <i class="fa-solid fa-file-circle-plus text-[#7A7670]"></i>
                <span class="hidden sm:inline">File</span>
            </button>
            <!-- Upload button -->
            <button @click="showUpload=true" class="h-9 px-4 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span class="hidden sm:inline">Upload</span>
            </button>
        </div>
    </div>

    <!-- Breadcrumbs + toolbar -->
    <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 px-4 py-3 border-b border-[#E8DFD1] bg-[#FCFBF9]">
            <nav class="flex items-center gap-1 text-[13px] font-mono overflow-x-auto min-w-0">
                <template x-for="crumb in breadcrumbs" :key="crumb.path">
                    <span class="flex items-center gap-1 shrink-0">
                        <a :href="'?path='+encodeURIComponent(crumb.path)" class="px-2 py-1 rounded-full hover:bg-white border border-transparent hover:border-[#E8DFD1] transition" :class="crumb.path===path ? 'bg-white border-[#E8DFD1] font-medium' : 'text-[#7A7670]'" x-text="crumb.name"></a>
                        <span class="text-[#D6CFC2]">/</span>
                    </span>
                </template>
            </nav>
            <!-- Search bar -->
            <div class="flex items-center gap-2 bg-white border border-[#E8DFD1] rounded-full pl-3 pr-2 py-1.5 w-full sm:w-[220px]">
                <i class="fa-solid fa-magnifying-glass text-[11px] text-[#7A7670] shrink-0"></i>
                <input
                    x-model="searchQuery"
                    @input="selectedFiles = []; $nextTick(() => updateCheckboxState())"
                    placeholder="Cari file..."
                    id="fm-search"
                    class="bg-transparent text-[13px] outline-none flex-1 placeholder:text-[#A8A29E] min-w-0"
                >
                <button x-show="searchQuery" @click="searchQuery=''; selectedFiles=[]; $nextTick(()=>updateCheckboxState())" class="w-5 h-5 rounded-full bg-[#F6F5F1] flex items-center justify-center text-[#7A7670] hover:bg-[#E8DFD1]">
                    <i class="fa-solid fa-xmark text-[9px]"></i>
                </button>
            </div>
        </div>

        <!-- Upload zone -->
        <div x-show="showUpload" x-cloak x-transition class="border-b border-[#E8DFD1] bg-amber-50/50 p-4">
            <form action="{{ $r_upload }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                @csrf
                <input type="hidden" name="path" :value="path">
                <label class="flex-1 w-full flex items-center gap-3 border-2 border-dashed border-[#E8DFD1] rounded-xl p-3 bg-white cursor-pointer">
                    <span class="w-10 h-10 rounded-lg bg-[#11100F] text-white flex items-center justify-center"><i class="fa-solid fa-cloud-arrow-up"></i></span>
                    <div class="flex-1">
                        <div class="text-[13px] font-medium">Drop file atau klik pilih</div>
                        <div class="text-[11px] text-[#7A7670]">Max 50MB per file</div>
                    </div>
                    <input type="file" name="files[]" multiple class="hidden" onchange="this.form.querySelector('#upload-name').textContent = Array.from(this.files).map(f=>f.name).join(', ')">
                </label>
                <div class="flex items-center gap-2">
                    <span id="upload-name" class="text-[11px] font-mono text-[#7A7670] hidden sm:block max-w-[200px] truncate">Belum ada file</span>
                    <button class="px-5 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black">Upload</button>
                    <button type="button" @click="showUpload=false" class="px-4 py-2.5 rounded-full border border-[#E8DFD1] bg-white text-[13px]">Tutup</button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#E8DFD1] text-[11px] font-semibold text-[#7A7670] uppercase tracking-wider">
                        <th class="pl-3 pr-1 py-3 w-8">
                            <input
                                type="checkbox"
                                id="cb-select-all"
                                @click="toggleSelectAll()"
                                x-effect="updateCheckboxState()"
                                class="rounded border-[#E8DFD1] text-[#11100F] focus:ring-[#11100F] cursor-pointer"
                            >
                        </th>
                        <th class="px-2 py-3">
                            <span x-show="!searchQuery">Nama File</span>
                            <span x-show="searchQuery" class="text-[#2563EB]" x-text="filteredItems.length + ' hasil untuk &quot;' + searchQuery + '&quot;'"></span>
                        </th>
                        <th class="px-3 py-3 w-32 hidden sm:table-cell">Ukuran</th>
                        <th class="px-3 py-3 w-40 hidden md:table-cell">Tanggal</th>
                        <th class="pr-3 pl-1 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($rel !== '')
                    <tr x-show="!searchQuery" class="border-b border-[#F6F5F1] hover:bg-[#F6F5F1]/50 transition-colors cursor-pointer" onclick="window.location='?path={{ urlencode(dirname($rel) === '.' ? '' : dirname($rel)) }}'">
                        <td class="pl-3 pr-1 py-3"></td>
                        <td class="px-2 py-3 flex items-center gap-2">
                            <div class="w-8 h-8 rounded bg-white border border-[#E8DFD1] flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-level-up-alt text-[#7A7670]"></i>
                            </div>
                            <span class="text-[13px] font-medium">.. (Kembali)</span>
                        </td>
                        <td class="px-3 py-3 hidden sm:table-cell"></td>
                        <td class="px-3 py-3 hidden md:table-cell"></td>
                        <td class="pr-3 pl-1 py-3"></td>
                    </tr>
                    @endif

                    {{-- Alpine-rendered rows from filteredItems --}}
                    <template x-for="it in filteredItems" :key="it.path">
                        <tr class="border-b border-[#F6F5F1] hover:bg-[#FCFBF9] transition-colors group">
                            <td class="pl-3 pr-1 py-3 w-8">
                                <input type="checkbox" :value="it.path" x-model="selectedFiles" @change="$nextTick(()=>updateCheckboxState())" class="rounded border-[#E8DFD1] text-[#11100F] focus:ring-[#11100F]">
                            </td>
                            <td class="px-2 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center text-[13px]"
                                        :class="it.is_dir ? 'bg-[#E6F0FF] border border-[#BFDBFE] text-[#2563EB]' : 'bg-white border border-[#E8DFD1] text-[#7A7670]'">
                                        <i :class="{
                                            'fa-solid fa-folder': it.is_dir,
                                            'fa-brands fa-html5 text-[#E84E0F]': !it.is_dir && (it.ext==='html'||it.ext==='htm'),
                                            'fa-brands fa-css3 text-[#2563EB]': !it.is_dir && it.ext==='css',
                                            'fa-brands fa-js text-amber-500': !it.is_dir && (it.ext==='js'||it.ext==='mjs'),
                                            'fa-regular fa-image': !it.is_dir && ['png','jpg','jpeg','webp','gif','svg'].includes(it.ext),
                                            'fa-solid fa-file-zipper': !it.is_dir && it.ext==='zip',
                                            'fa-regular fa-file': !it.is_dir && !['html','htm','css','js','mjs','png','jpg','jpeg','webp','gif','svg','zip'].includes(it.ext)
                                        }"></i>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <template x-if="it.is_dir">
                                            <a :href="'?path='+encodeURIComponent(it.path)" class="font-medium hover:text-[#2563EB] hover:underline block truncate text-[13px]" x-text="it.name"></a>
                                        </template>
                                        <template x-if="!it.is_dir">
                                            <div class="font-medium truncate text-[13px]" x-text="it.name"></div>
                                        </template>
                                        <div class="sm:hidden text-[10px] text-[#7A7670] font-mono" x-text="it.size + ' • ' + it.mtime"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3 hidden sm:table-cell font-mono text-[12px] whitespace-nowrap" x-text="it.size"></td>
                            <td class="px-3 py-3 hidden md:table-cell text-[12px] text-[#7A7670] whitespace-nowrap" x-text="it.mtime"></td>
                            <td class="pr-3 pl-1 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    {{-- Edit + Download for files, Open for dirs --}}
                                    <template x-if="!it.is_dir">
                                        <span class="flex items-center gap-1">
                                            <button @click="(($d,$it)=>{ fetch('{{ $r_edit }}?path='+encodeURIComponent($it.path)).then(r=>r.json()).then(d=>{ $d.editPath=d.path; $d.editName=d.name; $d.editContent=d.content; $d.showEditor=true; $nextTick(()=>{ initMonaco() }); }); })($data, it)" class="w-7 h-7 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center hover:bg-[#11100F] hover:text-white" title="Edit"><i class="fa-solid fa-pen text-[10px]"></i></button>
                                            <a :href="'{{ $r_download }}?path='+encodeURIComponent(it.path)" class="w-7 h-7 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center hover:bg-[#F6F5F1]" title="Download"><i class="fa-solid fa-download text-[10px]"></i></a>
                                        </span>
                                    </template>
                                    <template x-if="it.is_dir">
                                        <a :href="'?path='+encodeURIComponent(it.path)" class="w-7 h-7 rounded-full bg-[#11100F] text-white flex items-center justify-center" title="Buka"><i class="fa-solid fa-arrow-right text-[10px]"></i></a>
                                    </template>
                                    {{-- Rename --}}
                                    <button @click="renamePath=it.path; renameNew=it.name; showRename=true" class="w-7 h-7 rounded-full bg-white border border-[#E8DFD1] flex sm:hidden group-hover:flex items-center justify-center hover:bg-[#F6F5F1]" title="Rename"><i class="fa-solid fa-i-cursor text-[10px]"></i></button>
                                    {{-- Extract ZIP --}}
                                    <template x-if="it.ext === 'zip'">
                                        <form :action="'{{ $r_extract }}'" method="POST" @submit.prevent="confirmDelete($el, 'Ekstrak ZIP?', 'Semua isinya akan masuk ke folder ini.')">
                                            @csrf
                                            <input type="hidden" name="path" :value="it.path">
                                            <button type="submit" class="w-7 h-7 rounded-full bg-[#11100F] text-white flex items-center justify-center hover:bg-black" title="Extract"><i class="fa-solid fa-file-zipper text-[10px]"></i></button>
                                        </form>
                                    </template>
                                    {{-- Delete --}}
                                    <form :action="'{{ $r_delete }}'" method="POST" @submit.prevent="confirmDelete($el.closest('form'), 'Hapus File?', 'Yakin hapus ' + it.name + '?')">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="path" :value="it.path">
                                        <button type="submit" class="w-7 h-7 rounded-full bg-white border border-red-200 text-red-600 flex items-center justify-center hover:bg-red-50"><i class="fa-solid fa-trash text-[10px]"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </template>

                    {{-- No results state --}}
                    <tr x-show="filteredItems.length === 0">
                        <td colspan="5" class="px-6 py-12 text-center">
                            <template x-if="searchQuery">
                                <div>
                                    <div class="w-12 h-12 rounded-full bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center mx-auto mb-3"><i class="fa-solid fa-magnifying-glass text-[#7A7670]"></i></div>
                                    <div class="text-[13px] font-medium">Tidak ditemukan</div>
                                    <div class="text-[12px] text-[#7A7670]">Tidak ada file yang cocok dengan "<span x-text="searchQuery"></span>"</div>
                                    <button @click="searchQuery=''" class="mt-3 px-4 py-2 rounded-full bg-white border border-[#E8DFD1] text-[12px] hover:bg-[#F6F5F1]">Hapus filter</button>
                                </div>
                            </template>
                            <template x-if="!searchQuery">
                                <div>
                                    <div class="w-12 h-12 rounded-full bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center mx-auto mb-3"><i class="fa-regular fa-folder-open text-[#7A7670]"></i></div>
                                    <div class="text-[13px] font-medium">Folder kosong</div>
                                    <div class="text-[12px] text-[#7A7670]">Upload file atau buat file baru.</div>
                                </div>
                            </template>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-[#E8DFD1] bg-[#FCFBF9] flex items-center justify-between text-[12px] text-[#7A7670]">
            <span>
                <span x-show="!searchQuery">{{ count($items) }} item</span>
                <span x-show="searchQuery" x-text="filteredItems.length + ' dari {{ count($items) }} item'"></span>
                • {{ $usage['human'] }} / {{ $hosting->quota_mb }} MB
            </span>
            <span class="font-mono text-[11px] hidden sm:inline">{{ $hosting->path }}/{{ $rel }}</span>
        </div>
    </div>

    <!-- New Folder Modal -->
    <div x-show="showNewFolder" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div @click="showNewFolder=false" class="absolute inset-0 bg-[#141210]/40 backdrop-blur-sm"></div>
        <form action="{{ $r_mkdir }}" method="POST" class="relative bg-white rounded-xl border border-[#E8DFD1] p-6 w-full max-w-md shadow-2xl">
            @csrf<input type="hidden" name="path" :value="path">
            <h3 class="font-semibold text-[15px]">Buat Folder</h3>
            <input x-model="newFolderName" name="name" placeholder="nama-folder" required class="form-input mt-3 w-full">
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" @click="showNewFolder=false" class="px-4 py-2.5 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-medium hover:bg-[#F6F5F1]">Batal</button>
                <button class="px-6 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black">Buat</button>
            </div>
        </form>
    </div>

    <!-- New File Modal -->
    <div x-show="showNewFile" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div @click="showNewFile=false" class="absolute inset-0 bg-[#141210]/40 backdrop-blur-sm"></div>
        <form action="{{ $r_mkfile }}" method="POST" class="relative bg-white rounded-xl border border-[#E8DFD1] p-6 w-full max-w-lg shadow-2xl">
            @csrf<input type="hidden" name="path" :value="path">
            <h3 class="font-semibold text-[15px]">Buat File Baru</h3>
            <div class="mt-3 space-y-3">
                <input x-model="newFileName" name="name" placeholder="index.html" required class="form-input w-full font-mono text-[13px]">
                <textarea name="content" x-model="newFileContent" rows="5" class="form-input w-full font-mono text-[12px] leading-relaxed resize-none" placeholder="<!-- isi file opsional -->"></textarea>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" @click="showNewFile=false" class="px-4 py-2.5 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-medium hover:bg-[#F6F5F1]">Batal</button>
                <button class="px-6 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black">Buat File</button>
            </div>
        </form>
    </div>

    <!-- Rename Modal -->
    <div x-show="showRename" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div @click="showRename=false" class="absolute inset-0 bg-[#141210]/40 backdrop-blur-sm"></div>
        <form action="{{ $r_rename }}" method="POST" class="relative bg-white rounded-xl border border-[#E8DFD1] p-6 w-full max-w-md shadow-2xl">
            @csrf
            <input type="hidden" name="old_path" :value="renamePath">
            <h3 class="font-semibold text-[15px]">Ganti Nama</h3>
            <input x-model="renameNew" name="new_name" required class="form-input mt-3 w-full font-mono text-[13px]">
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" @click="showRename=false" class="px-4 py-2.5 rounded-full border border-[#E8DFD1] bg-white text-[13px] font-medium hover:bg-[#F6F5F1]">Batal</button>
                <button class="px-6 py-2.5 rounded-full bg-[#11100F] text-white text-[13px] font-medium hover:bg-black">Simpan</button>
            </div>
        </form>
    </div>

    <!-- Editor Modal - VSCode Style -->
    <div x-show="showEditor" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-0 lg:p-8">
        <div @click="showEditor=false" class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-[1100px] h-full lg:h-[88vh] flex flex-col overflow-hidden shadow-2xl" style="border-radius: 8px; border: 1px solid #3c3c3c;">

            <!-- VSCode Title Bar -->
            <div class="flex items-center gap-0 shrink-0" style="background:#323233; height:30px; padding: 0 12px; border-bottom: 1px solid #1e1e1e;">
                <!-- Traffic light dots -->
                <div class="flex items-center gap-1.5 mr-4">
                    <button @click="showEditor=false" class="w-3 h-3 rounded-full flex items-center justify-center group" style="background:#ff5f57;">
                        <i class="fa-solid fa-xmark text-[6px] text-red-900 opacity-0 group-hover:opacity-100"></i>
                    </button>
                    <div class="w-3 h-3 rounded-full" style="background:#febc2e;"></div>
                    <div class="w-3 h-3 rounded-full" style="background:#28c840;"></div>
                </div>
                <!-- Window title -->
                <div class="flex-1 text-center">
                    <span class="text-[12px]" style="color:#cccccc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;" x-text="editName + ' - File Manager'"></span>
                </div>
            </div>

            <!-- VSCode Tab Bar -->
            <div class="flex items-end shrink-0" style="background:#2d2d2d; border-bottom: 1px solid #1e1e1e; height: 35px;">
                <div class="flex items-center px-4 h-full border-t-2 gap-2" style="background:#1e1e1e; border-top-color:#0078d4; border-right: 1px solid #3c3c3c; min-width: 160px; max-width: 220px;">
                    <i class="fa-solid fa-file-code text-[11px]" style="color:#cccccc;"></i>
                    <span class="text-[12px] truncate" style="color:#ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;" x-text="editName"></span>
                    <div class="ml-auto w-4 h-4 rounded flex items-center justify-center hover:bg-white/10 cursor-pointer" @click="showEditor=false">
                        <i class="fa-solid fa-xmark text-[9px]" style="color:#cccccc;"></i>
                    </div>
                </div>
                <!-- Inactive tabs area + toolbar -->
                <div class="flex-1 h-full flex items-center justify-end px-2 gap-1" style="background:#2d2d2d;">
                    <!-- Word Wrap Toggle -->
                    <button onclick="toggleWrap()" id="btn-wrap" title="Toggle Word Wrap (Alt+Z)"
                        class="flex items-center gap-1 px-2 h-6 rounded text-[11px] hover:bg-white/10"
                        style="color:#cccccc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">
                        <i class="fa-solid fa-arrow-turn-down text-[9px]"></i>
                        <span class="hidden sm:inline">Wrap</span>
                    </button>
                    <!-- Format Document -->
                    <button onclick="formatEditor()" title="Format Document (Alt+Shift+F)"
                        class="flex items-center gap-1 px-2 h-6 rounded text-[11px] hover:bg-white/10"
                        style="color:#cccccc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">
                        <i class="fa-solid fa-wand-magic-sparkles text-[9px]"></i>
                        <span class="hidden sm:inline">Format</span>
                    </button>
                </div>
            </div>

            <!-- VSCode Breadcrumb -->
            <div class="flex items-center px-3 shrink-0" style="background:#1e1e1e; height: 22px; border-bottom: 1px solid #3c3c3c;">
                <i class="fa-solid fa-folder text-[9px] mr-1" style="color:#dcb67a;"></i>
                <span class="text-[11px]" style="color:#888888; font-family: 'Consolas', 'Fira Code', monospace;" x-text="'hosting / ' + editPath"></span>
            </div>

            <!-- Monaco Editor -->
            <div id="monaco-editor" class="flex-1 min-h-0" style="background:#1e1e1e;"></div>

            <!-- VSCode Status Bar -->
            <div class="flex items-center justify-between shrink-0 px-3" style="background:#0078d4; height: 22px;">
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-1 text-[11px]" style="color:#ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">
                        <i class="fa-solid fa-code-branch text-[9px]"></i> main
                    </span>
                    <span class="text-[11px]" style="color:#ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;" id="editor-status">Ready</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-[11px]" style="color:#ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;" id="editor-cursor">Ln 1, Col 1</span>
                    <span class="text-[11px]" style="color:#ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;" id="editor-lang">Plain Text</span>
                    <span class="text-[11px]" style="color:#ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">UTF-8</span>
                    <button onclick="formatEditor()" class="flex items-center gap-1 px-2 py-0.5 rounded text-[11px] hover:bg-white/20" style="color:#ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;" title="Format (Alt+Shift+F)">
                        <i class="fa-solid fa-wand-magic-sparkles text-[9px]"></i> Format
                    </button>
                    <button onclick="saveEditor()" class="flex items-center gap-1 px-2 py-0.5 rounded text-[11px] hover:bg-white/20" style="color:#ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">
                        <i class="fa-solid fa-floppy-disk text-[9px]"></i> Ctrl+S
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden save form --}}
    <form id="edit-form" action="{{ $r_update }}" method="POST" class="hidden">
        @csrf
        @method('PUT')
        <input type="hidden" name="path" x-bind:value="editPath">
        <input type="hidden" name="content" value="">
    </form>
</div>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.min.js"></script>
<script>
let monacoInstance = null;

function fmData() {
    return Alpine.$data(document.getElementById('fm-root'));
}

const LANG_MAP = {
    css: { id: 'css', label: 'CSS' },
    js: { id: 'javascript', label: 'JavaScript' },
    mjs: { id: 'javascript', label: 'JavaScript' },
    json: { id: 'json', label: 'JSON' },
    html: { id: 'html', label: 'HTML' },
    htm: { id: 'html', label: 'HTML' },
    php: { id: 'php', label: 'PHP' },
    md: { id: 'markdown', label: 'Markdown' },
    xml: { id: 'xml', label: 'XML' },
    sql: { id: 'sql', label: 'SQL' },
    ts: { id: 'typescript', label: 'TypeScript' },
    py: { id: 'python', label: 'Python' },
    sh: { id: 'shell', label: 'Shell' },
    yml: { id: 'yaml', label: 'YAML' },
    yaml: { id: 'yaml', label: 'YAML' },
};

function detectLangInfo(name) {
    if (!name) return { id: 'plaintext', label: 'Plain Text' };
    const ext = name.split('.').pop().toLowerCase();
    return LANG_MAP[ext] || { id: 'plaintext', label: 'Plain Text' };
}

function detectLang(name) { return detectLangInfo(name).id; }

function updateStatusBar(editor, name) {
    const langInfo = detectLangInfo(name);
    const langEl = document.getElementById('editor-lang');
    const cursorEl = document.getElementById('editor-cursor');
    const statusEl = document.getElementById('editor-status');
    if (langEl) langEl.textContent = langInfo.label;
    if (statusEl) statusEl.textContent = 'Ready';
    if (editor && cursorEl) {
        editor.onDidChangeCursorPosition((e) => {
            cursorEl.textContent = `Ln ${e.position.lineNumber}, Col ${e.position.column}`;
        });
    }
}

function initMonaco() {
    const data = fmData();
    if (monacoInstance) {
        const model = monacoInstance.getModel();
        monaco.editor.setModelLanguage(model, detectLang(data.editName));
        monacoInstance.setValue(data.editContent || '');
        updateStatusBar(monacoInstance, data.editName);
        return;
    }
    require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs' } });
    require(['vs/editor/editor.main'], function () {
        const d = fmData();
        monacoInstance = monaco.editor.create(document.getElementById('monaco-editor'), {
            value: d.editContent || '',
            language: detectLang(d.editName),
            theme: 'vs-dark',
            automaticLayout: true,
            minimap: { enabled: true, scale: 1 },
            fontSize: 14,
            fontFamily: "'Fira Code', 'Cascadia Code', 'JetBrains Mono', Consolas, monospace",
            fontLigatures: true,
            wordWrap: 'on',
            lineNumbers: 'on',
            scrollBeyondLastLine: false,
            renderLineHighlight: 'all',
            cursorBlinking: 'smooth',
            cursorSmoothCaretAnimation: 'on',
            smoothScrolling: true,
            tabSize: 4,
            insertSpaces: true,
            bracketPairColorization: { enabled: true },
            guides: { bracketPairs: true, indentation: true },
            renderWhitespace: 'selection',
            folding: true,
            padding: { top: 10 },
        });
        monacoInstance.onDidChangeModelContent(() => {
            fmData().editContent = monacoInstance.getValue();
            const statusEl = document.getElementById('editor-status');
            if (statusEl) statusEl.textContent = '● Modified';
        });
        updateStatusBar(monacoInstance, d.editName);
        // Keyboard shortcuts
        monacoInstance.addCommand(monaco.KeyMod.CtrlCmd | monaco.KeyCode.KeyS, () => { saveEditor(); });
        monacoInstance.addCommand(monaco.KeyMod.Alt | monaco.KeyMod.Shift | monaco.KeyCode.KeyF, () => { formatEditor(); });
        monacoInstance.addCommand(monaco.KeyMod.Alt | monaco.KeyCode.KeyZ, () => { toggleWrap(); });
    });
}

function saveEditor() {
    const data = fmData();
    if (monacoInstance) data.editContent = monacoInstance.getValue();
    const statusEl = document.getElementById('editor-status');
    if (statusEl) statusEl.textContent = 'Saving...';
    document.querySelector('#edit-form input[name="content"]').value = data.editContent || '';
    document.getElementById('edit-form').submit();
}

let _wrapEnabled = true;
function toggleWrap() {
    if (!monacoInstance) return;
    _wrapEnabled = !_wrapEnabled;
    monacoInstance.updateOptions({ wordWrap: _wrapEnabled ? 'on' : 'off' });
    const btn = document.getElementById('btn-wrap');
    if (btn) {
        btn.style.background = _wrapEnabled ? 'rgba(255,255,255,0.15)' : '';
        btn.style.borderRadius = '4px';
    }
    const statusEl = document.getElementById('editor-status');
    if (statusEl) statusEl.textContent = _wrapEnabled ? 'Word Wrap: ON' : 'Word Wrap: OFF';
    setTimeout(() => { if (statusEl) statusEl.textContent = 'Ready'; }, 1500);
}

function formatEditor() {
    if (!monacoInstance) return;
    const statusEl = document.getElementById('editor-status');
    if (statusEl) statusEl.textContent = 'Formatting...';
    monacoInstance.getAction('editor.action.formatDocument').run().then(() => {
        if (statusEl) statusEl.textContent = '✓ Formatted';
        setTimeout(() => { if (statusEl) statusEl.textContent = 'Ready'; }, 1500);
    }).catch(() => {
        // Fallback: manual basic formatting for HTML
        if (statusEl) statusEl.textContent = 'No formatter available';
        setTimeout(() => { if (statusEl) statusEl.textContent = 'Ready'; }, 2000);
    });
}
</script>
@endsection
