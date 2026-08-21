@extends('layouts.admin')
@section('title', 'File Manager - ' . $hosting->student->name)
@section('breadcrumb', 'Hosting / File Manager')
@section('content')
<div x-data="{ 
    path: @js($rel),
    showNewFolder:false, newFolderName:'',
    showNewFile:false, newFileName:'', newFileContent:'',
    showRename:false, renamePath:'', renameNew:'',
    showEditor:false, editPath:'', editContent:'', editName:'',
    showUpload:false,
    breadcrumbs: @js($breadcrumbs),
    navigate(p){ window.location='?path='+encodeURIComponent(p) },
 }" class="max-w-[1280px]">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('hostings.show', $hosting->hash_id) }}" class="w-9 h-9 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center hover:bg-[#F6F5F1]"><i class="fa-solid fa-arrow-left text-[12px]"></i></a>
            <div>
                <h1 class="font-semibold text-[16px] leading-none flex items-center gap-2"><i class="fa-solid fa-folder-open text-[#2563EB]"></i> File Manager</h1>
                <p class="font-mono text-[11px] text-[#7A7670]">{{ $hosting->student->nim }} • {{ $hosting->path }} • {{ $usage['human'] }} / {{ $hosting->quota_mb }} MB</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ $hosting->url }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 h-[36px] px-4 rounded-full bg-emerald-500 text-white text-[12px] font-medium hover:bg-emerald-600"><i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> Buka Site</a>
            <button @click="showUpload=!showUpload" class="h-[36px] px-4 rounded-full bg-[#11100F] text-white text-[12px] font-medium hover:bg-black"><i class="fa-solid fa-cloud-arrow-up mr-1"></i> Upload</button>
        </div>
    </div>

    <!-- Breadcrumbs + toolbar -->
    <div class="bg-white border border-[#E8DFD1] rounded-xl overflow-hidden shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 px-4 py-3 border-b border-[#E8DFD1] bg-[#FCFBF9]">
            <nav class="flex items-center gap-1 text-[13px] font-mono overflow-x-auto">
                <template x-for="crumb in breadcrumbs" :key="crumb.path">
                    <span class="flex items-center gap-1">
                        <a :href="'?path='+encodeURIComponent(crumb.path)" class="px-2 py-1 rounded-full hover:bg-white border border-transparent hover:border-[#E8DFD1] transition" :class="crumb.path===path ? 'bg-white border-[#E8DFD1] font-medium' : 'text-[#7A7670]'" x-text="crumb.name"></a>
                        <span class="text-[#D6CFC2]">/</span>
                    </span>
                </template>
            </nav>
            <div class="flex items-center gap-1.5">
                <button @click="showNewFolder=true" class="h-[32px] px-3 rounded-full bg-white border border-[#E8DFD1] text-[12px] font-medium hover:bg-[#F6F5F1]"><i class="fa-solid fa-folder-plus mr-1 text-[11px]"></i> Folder</button>
                <button @click="showNewFile=true" class="h-[32px] px-3 rounded-full bg-white border border-[#E8DFD1] text-[12px] font-medium hover:bg-[#F6F5F1]"><i class="fa-solid fa-file-circle-plus mr-1 text-[11px]"></i> File</button>
                <span class="hidden sm:inline-flex items-center gap-1.5 text-[11px] font-mono bg-white border border-[#E8DFD1] rounded-full px-2.5 py-1"><span class="w-2 h-2 rounded-full {{ $hosting->status==='active'?'bg-emerald-500':'bg-red-500' }}"></span> {{ $usage['files'] }} files</span>
            </div>
        </div>

        <!-- Upload zone -->
        <div x-show="showUpload" x-cloak x-transition class="border-b border-[#E8DFD1] bg-amber-50/50 p-4">
            <form action="{{ route('hostings.files.upload', $hosting->hash_id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                @csrf
                <input type="hidden" name="path" :value="path">
                <label class="flex-1 w-full flex items-center gap-3 border-2 border-dashed border-[#E8DFD1] rounded-xl p-3 bg-white cursor-pointer">
                    <span class="w-10 h-10 rounded-lg bg-[#11100F] text-white flex items-center justify-center"><i class="fa-solid fa-cloud-arrow-up"></i></span>
                    <div class="flex-1">
                        <div class="text-[13px] font-medium">Drop file atau klik pilih</div>
                        <div class="text-[11px] text-[#7A7670]">Max 50MB per file, auto extract ZIP? upload manual</div>
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
            <table class="w-full text-left">
                <thead class="bg-[#F6F5F1] border-b border-[#E8DFD1] text-[11px] font-semibold tracking-[0.08em] uppercase text-[#7A7670]">
                    <tr>
                        <th class="px-4 py-3 w-8"><input type="checkbox" class="rounded"></th>
                        <th class="px-3 py-3">Name</th>
                        <th class="px-3 py-3 hidden sm:table-cell">Size</th>
                        <th class="px-3 py-3 hidden lg:table-cell">Modified</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E8DFD1]/60 text-[13px]">
                    @if($rel !== '')
                    <tr class="hover:bg-[#FCFBF9]">
                        <td class="px-4 py-3"></td>
                        <td class="px-3 py-3"><a href="?path={{ urlencode(dirname($rel) === '.' ? '' : dirname($rel)) }}" class="flex items-center gap-2 font-medium text-[#2563EB] hover:underline"><i class="fa-solid fa-turn-up text-[12px]"></i> .. (up)</a></td>
                        <td class="px-3 py-3 hidden sm:table-cell">—</td>
                        <td class="px-3 py-3 hidden lg:table-cell">—</td>
                        <td class="px-4 py-3"></td>
                    </tr>
                    @endif
                    @forelse($items as $it)
                    <tr class="hover:bg-[#FCFBF9] group">
                        <td class="px-4 py-3"><input type="checkbox" class="rounded"></td>
                        <td class="px-3 py-3">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center text-[13px] {{ $it['is_dir'] ? 'bg-[#E6F0FF] border border-[#BFDBFE] text-[#2563EB]' : 'bg-white border border-[#E8DFD1] text-[#7A7670]' }}">
                                    @if($it['is_dir'])<i class="fa-solid fa-folder"></i>
                                    @elseif(in_array($it['ext'], ['html','htm']))<i class="fa-brands fa-html5 text-[#E84E0F]"></i>
                                    @elseif($it['ext']==='css')<i class="fa-brands fa-css3 text-[#2563EB]"></i>
                                    @elseif(in_array($it['ext'], ['js','mjs']))<i class="fa-brands fa-js text-amber-500"></i>
                                    @elseif(in_array($it['ext'], ['png','jpg','jpeg','webp','gif','svg']))<i class="fa-regular fa-image"></i>
                                    @elseif($it['ext']==='zip')<i class="fa-solid fa-file-zipper"></i>
                                    @else<i class="fa-regular fa-file"></i>
                                    @endif
                                </span>
                                <div class="min-w-0">
                                    @if($it['is_dir'])
                                        <a href="?path={{ urlencode($it['path']) }}" class="font-medium hover:text-[#2563EB] hover:underline truncate max-w-[220px] block">{{ $it['name'] }}</a>
                                        <div class="text-[11px] text-[#7A7670]">Folder</div>
                                    @else
                                        <div class="font-medium truncate max-w-[220px]">{{ $it['name'] }}</div>
                                        <div class="text-[11px] font-mono text-[#7A7670]">{{ $it['ext'] }} • {{ $it['size'] }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-3 hidden sm:table-cell font-mono text-[12px]">{{ $it['size'] }}</td>
                        <td class="px-3 py-3 hidden lg:table-cell text-[12px] text-[#7A7670]">{{ $it['mtime'] }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                @if(!$it['is_dir'])
                                    <button @click="fetch('{{ route('hostings.files.edit', $hosting->hash_id) }}?path='+encodeURIComponent('{{ $it['path'] }}')).then(r=>r.json()).then(d=>{editPath=d.path; editName=d.name; editContent=d.content; showEditor=true; $nextTick(()=>{ if(window.monaco) initMonaco() }) })" class="w-7 h-7 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center hover:bg-[#11100F] hover:text-white" title="Edit"><i class="fa-solid fa-pen text-[10px]"></i></button>
                                    <a href="{{ route('hostings.files.download', $hosting->hash_id) }}?path={{ urlencode($it['path']) }}" class="w-7 h-7 rounded-full bg-white border border-[#E8DFD1] flex items-center justify-center hover:bg-[#F6F5F1]" title="Download"><i class="fa-solid fa-download text-[10px]"></i></a>
                                @else
                                    <a href="?path={{ urlencode($it['path']) }}" class="w-7 h-7 rounded-full bg-[#11100F] text-white flex items-center justify-center" title="Buka"><i class="fa-solid fa-arrow-right text-[10px]"></i></a>
                                @endif
                                <button @click="renamePath='{{ $it['path'] }}'; renameNew='{{ $it['name'] }}'; showRename=true" class="w-7 h-7 rounded-full bg-white border border-[#E8DFD1] hidden group-hover:flex items-center justify-center hover:bg-[#F6F5F1]" title="Rename"><i class="fa-solid fa-i-cursor text-[10px]"></i></button>
                                <form action="{{ route('hostings.files.destroy', $hosting->hash_id) }}" method="POST" onsubmit="return confirmDelete(this, 'Hapus File?', 'Yakin hapus {{ $it['name'] }}?')">
                                    @csrf @method('DELETE')
                                    <input type="hidden" name="path" value="{{ $it['path'] }}">
                                    <button class="w-7 h-7 rounded-full bg-white border border-red-200 text-red-600 flex items-center justify-center hover:bg-red-50"><i class="fa-solid fa-trash text-[10px]"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center">
                        <div class="w-12 h-12 rounded-full bg-[#F6F5F1] border border-[#E8DFD1] flex items-center justify-center mx-auto mb-3"><i class="fa-regular fa-folder-open text-[#7A7670]"></i></div>
                        <div class="text-[13px] font-medium">Folder kosong</div>
                        <div class="text-[12px] text-[#7A7670]">Upload file atau buat file baru.</div>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-[#E8DFD1] bg-[#FCFBF9] flex items-center justify-between text-[12px] text-[#7A7670]">
            <span>{{ count($items) }} item • {{ $usage['human'] }} / {{ $hosting->quota_mb }} MB</span>
            <span class="font-mono text-[11px] hidden sm:inline">{{ $hosting->path }}/{{ $rel }}</span>
        </div>
    </div>

    <!-- New Folder Modal -->
    <div x-show="showNewFolder" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="showNewFolder=false" class="absolute inset-0 bg-[#141210]/40 backdrop-blur-sm"></div>
        <form action="{{ route('hostings.files.mkdir', $hosting->hash_id) }}" method="POST" class="relative bg-white rounded-xl border border-[#E8DFD1] p-6 w-full max-w-[400px]">
            @csrf<input type="hidden" name="path" :value="path">
            <h3 class="font-semibold text-[15px]">Buat Folder</h3>
            <input x-model="newFolderName" name="name" placeholder="nama-folder" required class="form-input mt-3">
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" @click="showNewFolder=false" class="px-4 py-2 rounded-full border border-[#E8DFD1] bg-white text-[13px]">Batal</button>
                <button class="px-5 py-2 rounded-full bg-[#11100F] text-white text-[13px]">Buat</button>
            </div>
        </form>
    </div>

    <!-- New File Modal -->
    <div x-show="showNewFile" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="showNewFile=false" class="absolute inset-0 bg-[#141210]/40 backdrop-blur-sm"></div>
        <form action="{{ route('hostings.files.mkfile', $hosting->hash_id) }}" method="POST" class="relative bg-white rounded-xl border border-[#E8DFD1] p-6 w-full max-w-[500px]">
            @csrf<input type="hidden" name="path" :value="path">
            <h3 class="font-semibold text-[15px]">Buat File Baru</h3>
            <input x-model="newFileName" name="name" placeholder="index.html" required class="form-input mt-3">
            <textarea x-model="newFileContent" name="content" placeholder="<html>..." rows="6" class="form-input mt-3 font-mono text-[12px]"></textarea>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" @click="showNewFile=false" class="px-4 py-2 rounded-full border border-[#E8DFD1] bg-white text-[13px]">Batal</button>
                <button class="px-5 py-2 rounded-full bg-[#11100F] text-white text-[13px]">Buat</button>
            </div>
        </form>
    </div>

    <!-- Rename Modal -->
    <div x-show="showRename" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="showRename=false" class="absolute inset-0 bg-[#141210]/40 backdrop-blur-sm"></div>
        <form action="{{ route('hostings.files.rename', $hosting->hash_id) }}" method="POST" class="relative bg-white rounded-xl border border-[#E8DFD1] p-6 w-full max-w-[400px]">
            @csrf<input type="hidden" name="path" :value="renamePath">
            <h3 class="font-semibold text-[15px]">Rename</h3>
            <input x-model="renameNew" name="new_name" required class="form-input mt-3">
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" @click="showRename=false" class="px-4 py-2 rounded-full border bg-white text-[13px]">Batal</button>
                <button class="px-5 py-2 rounded-full bg-[#11100F] text-white text-[13px]">Simpan</button>
            </div>
        </form>
    </div>

    <!-- Editor Modal with Monaco -->
    <div x-show="showEditor" x-cloak class="fixed inset-0 z-50 flex flex-col">
        <div @click="showEditor=false" class="absolute inset-0 bg-[#141210]/60 backdrop-blur-sm"></div>
        <div class="relative bg-white w-full h-full flex flex-col">
            <div class="h-[56px] border-b border-[#E8DFD1] flex items-center gap-3 px-4 bg-[#FCFBF9] shrink-0">
                <span class="w-8 h-8 rounded-lg bg-[#11100F] text-white flex items-center justify-center"><i class="fa-solid fa-code text-[12px]"></i></span>
                <div class="min-w-0 flex-1">
                    <div class="font-mono text-[13px] font-medium truncate" x-text="editName"></div>
                    <div class="font-mono text-[11px] text-[#7A7670] truncate" x-text="editPath"></div>
                </div>
                <button @click="showEditor=false" class="w-9 h-9 rounded-full border border-[#E8DFD1] bg-white flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div id="monaco-editor" class="flex-1 min-h-0"></div>
            <div class="p-3 border-t border-[#E8DFD1] bg-[#FCFBF9] flex items-center justify-between">
                <span class="text-[11px] font-mono text-[#7A7670]">Monaco Editor • Ctrl+S simpan</span>
                <div class="flex gap-2">
                    <button @click="showEditor=false" class="px-4 py-2 rounded-full border border-[#E8DFD1] bg-white text-[13px]">Tutup</button>
                    <button @click="saveEditor()" class="px-6 py-2 rounded-full bg-[#11100F] text-white text-[13px] font-medium"><i class="fa-solid fa-floppy-disk mr-1"></i> Simpan</button>
                </div>
            </div>
            <form id="edit-form" action="{{ route('hostings.files.update', $hosting->hash_id) }}" method="POST" class="hidden">
                @csrf @method('PUT')
                <input type="hidden" name="path" :value="editPath">
                <input type="hidden" name="content" :value="editContent">
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.min.js"></script>
<script>
let monacoInstance = null;
function initMonaco(){
    if(monacoInstance) { monacoInstance.setValue(document.querySelector('[x-data]').__x.$data.editContent); return; }
    require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs' }});
    require(['vs/editor/editor.main'], function () {
        const data = Alpine.$data(document.querySelector('[x-data]'));
        monacoInstance = monaco.editor.create(document.getElementById('monaco-editor'), {
            value: data.editContent,
            language: data.editName.endsWith('.css') ? 'css' : data.editName.endsWith('.js') ? 'javascript' : data.editName.endsWith('.json') ? 'json' : data.editName.endsWith('.html') ? 'html' : 'plaintext',
            theme: 'vs-light',
            automaticLayout: true,
            minimap: { enabled: false },
            fontSize: 13,
        });
        monacoInstance.onDidChangeModelContent(()=>{ data.editContent = monacoInstance.getValue(); });
        // Ctrl+S
        monacoInstance.addCommand(monaco.KeyMod.CtrlCmd | monaco.KeyCode.KeyS, ()=>{ saveEditor(); });
    });
}
function saveEditor(){
    const data = Alpine.$data(document.querySelector('[x-data]'));
    if(monacoInstance) data.editContent = monacoInstance.getValue();
    document.querySelector('#edit-form input[name="content"]').value = data.editContent;
    document.getElementById('edit-form').submit();
}
</script>
@endsection
