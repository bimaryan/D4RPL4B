<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Support\WebpConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::orderBy('nim')->get();
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|unique:students|max:20',
            'name' => 'required|max:255',
            'github_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'photo_url' => 'nullable|url|max:512',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = WebpConverter::store($request->file('photo'), 'students', 800, 82);
        } elseif (!empty($validated['photo_url'])) {
            $validated['photo'] = $validated['photo_url'];
        }
        unset($validated['photo_url']);

        $student = Student::create($validated);

        // Auto buat user login per mahasiswa: NIM sebagai username & password = NIM
        User::create([
            'name' => $student->name,
            'email' => $student->nim . '@student.polindra.ac.id',
            'nim' => $student->nim,
            'student_id' => $student->id,
            'password' => Hash::make($student->nim),
            'role' => 'mahasiswa',
        ]);

        return redirect()->route('students.index')->with('success', 'Mahasiswa berhasil ditambahkan. Login: NIM ' . $student->nim . ' / Pass: ' . $student->nim);
    }

    public function show(Student $student)
    {
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'nim' => 'required|max:20|unique:students,nim,' . $student->id,
            'name' => 'required|max:255',
            'github_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'photo_url' => 'nullable|url|max:512',
            'remove_photo' => 'nullable|boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($student->photo && !str_starts_with($student->photo, 'http')) {
                Storage::disk('public')->delete($student->photo);
            }
            $validated['photo'] = WebpConverter::store($request->file('photo'), 'students', 800, 82);
        } elseif (!empty($validated['photo_url'])) {
            if ($student->photo && !str_starts_with($student->photo, 'http')) {
                Storage::disk('public')->delete($student->photo);
            }
            $validated['photo'] = $validated['photo_url'];
        } elseif ($request->boolean('remove_photo')) {
            if ($student->photo && !str_starts_with($student->photo, 'http')) {
                Storage::disk('public')->delete($student->photo);
            }
            $validated['photo'] = null;
        } else {
            unset($validated['photo']);
        }
        unset($validated['photo_url'], $validated['remove_photo']);

        $oldNim = $student->nim;
        $student->update($validated);

        // Sync user login (NIM = password)
        $user = $student->user;
        if ($user) {
            $user->update([
                'name' => $student->name,
                'nim' => $student->nim,
                'email' => $student->nim . '@student.polindra.ac.id',
                // jika NIM berubah, reset password ke NIM baru
                'password' => $oldNim !== $student->nim ? Hash::make($student->nim) : $user->password,
            ]);
        } else {
            // Jika belum ada user (data lama), buat baru
            User::create([
                'name' => $student->name,
                'email' => $student->nim . '@student.polindra.ac.id',
                'nim' => $student->nim,
                'student_id' => $student->id,
                'password' => Hash::make($student->nim),
                'role' => 'mahasiswa',
            ]);
        }

        return redirect()->route('students.index')->with('success', 'Mahasiswa berhasil diupdate. Login tetap NIM: ' . $student->nim);
    }

    public function destroy(Student $student)
    {
        if ($student->photo && !str_starts_with($student->photo, 'http')) {
            Storage::disk('public')->delete($student->photo);
        }
        // Hapus user login & hosting terkait
        if ($student->user) {
            $student->user->delete();
        }
        if ($student->hosting) {
            $path = storage_path('app/public/' . $student->hosting->path);
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            }
            $student->hosting->delete();
        }
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Mahasiswa & akun login dihapus.');
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
