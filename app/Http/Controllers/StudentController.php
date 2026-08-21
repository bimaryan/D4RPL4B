<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Support\WebpConverter;
use Illuminate\Http\Request;
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

        Student::create($validated);
        return redirect()->route('students.index')->with('success', 'Mahasiswa berhasil ditambahkan.');
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

        $student->update($validated);
        return redirect()->route('students.index')->with('success', 'Mahasiswa berhasil diupdate.');
    }

    public function destroy(Student $student)
    {
        if ($student->photo && !str_starts_with($student->photo, 'http')) {
            Storage::disk('public')->delete($student->photo);
        }
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
