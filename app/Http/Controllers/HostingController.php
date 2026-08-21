<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Hosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HostingController extends Controller
{
    public function index()
    {
        $students = Student::with('hosting')->orderBy('nim')->get();
        $hostings = Hosting::with('student')->latest()->get();
        return view('admin.hostings.index', compact('students', 'hostings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id|unique:hostings,student_id',
            'domain' => 'nullable|string|max:255|unique:hostings,domain|regex:/^[a-z0-9\-\.]+$/',
            'quota_mb' => 'nullable|integer|min:100|max:5000',
        ]);

        $student = Student::findOrFail($validated['student_id']);
        $studentHash = $student->hash_id;
        $path = 'hostings/' . $studentHash;

        $full = storage_path('app/public/' . $path);
        if (!is_dir($full)) {
            mkdir($full, 0755, true);
            // default index
            file_put_contents($full . '/index.html', "<html><head><title>{$student->name} — D4 RPL 4B</title><style>body{font-family:system-ui;background:#FDF9F3;color:#141210;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0} .card{background:white;border:1px solid #E8DFD1;border-radius:16px;padding:32px;max-width:480px;text-align:center} h1{font-size:24px;margin:0 0 8px} p{color:#7A7670}</style></head><body><div class=card><h1>👋 Halo, {$student->name}</h1><p>NIM {$student->nim} — Hosting aktif di Polindra D4 RPL 4B.<br>Upload file via cPanel.</p><p style='margin-top:16px'><a href='/' style='color:#E84E0F'>← Kembali ke landing</a></p></div></body></html>");
        }

        $hosting = Hosting::create([
            'student_id' => $student->id,
            'domain' => $validated['domain'] ?? strtolower($student->nim) . '.d4rpl4b.test',
            'path' => $path,
            'quota_mb' => $validated['quota_mb'] ?? 500,
        ]);

        return redirect()->route('hostings.index')->with('success', "Hosting untuk {$student->name} berhasil dibuat. Path: {$path}");
    }

    public function show(Hosting $hosting)
    {
        $hosting->load('student');
        $usage = $hosting->diskUsage();
        return view('admin.hostings.show', compact('hosting', 'usage'));
    }

    public function destroy(Hosting $hosting)
    {
        $path = storage_path('app/public/' . $hosting->path);
        if (is_dir($path)) {
            $this->deleteDirectory($path);
        }
        $hosting->delete();
        return redirect()->route('hostings.index')->with('success', 'Hosting dihapus.');
    }

    public function toggle(Hosting $hosting)
    {
        $hosting->status = $hosting->status === 'active' ? 'suspended' : 'active';
        $hosting->save();
        return back()->with('success', 'Status hosting diubah ke ' . $hosting->status);
    }

    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) return;
        $items = array_diff(scandir($dir), ['.', '..']);
        foreach ($items as $item) {
            $p = $dir . '/' . $item;
            is_dir($p) ? $this->deleteDirectory($p) : unlink($p);
        }
        rmdir($dir);
    }
}
