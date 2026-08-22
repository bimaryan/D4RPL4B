<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Models\Student;
use App\Models\Project;
use App\Models\Announcement;
use App\Models\Gallery;
use App\Models\Schedule;

// Wildcard Subdomain Routing for Hostings (e.g., nim.d4rpl4b.ryaze.cloud)
$appHost = parse_url(config('app.url', 'http://d4rpl4b.ryaze.cloud'), PHP_URL_HOST) ?? 'd4rpl4b.ryaze.cloud';
Route::domain('{subdomain}.' . $appHost)->group(function () {
    Route::get('{any?}', [\App\Http\Controllers\HostingServeController::class, 'serveSubdomain'])->where('any', '.*');
});

// Hosting per Mahasiswa - serve like cPanel (fallback path-based)
Route::get('/hosting/{hash}/{any?}', [\App\Http\Controllers\HostingServeController::class, 'show'])->where('any', '.*')->name('hosting.show');

// Portfolio Hosting - serve project portfolios
Route::get('/portfolio/{hash}/{any?}', [\App\Http\Controllers\PortfolioController::class, 'show'])->where('any', '.*')->name('portfolio.show');

// Public Landing Page
Route::get('/', function () {
    $students = Student::orderBy('nim')->get();
    $projects = Project::latest()->get();
    $announcements = Announcement::orderBy('event_date', 'asc')->get();
    $galleries = Gallery::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
    $schedules = Schedule::orderBy('sort_order')->orderBy('id')->get();

    return view('welcome', compact('students', 'projects', 'announcements', 'galleries', 'schedules'));
});

// Authentication Routes
Route::middleware('guest:web,student')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:web,student');

// ============================================================
// ADMIN PANEL - Only for Admin (web guard + role=admin)
// Prefix: /admin
// ============================================================
Route::middleware(['auth:web', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    Route::get('/hero', [\App\Http\Controllers\HeroSettingController::class, 'edit'])->name('hero.edit');
    Route::put('/hero', [\App\Http\Controllers\HeroSettingController::class, 'update'])->name('hero.update');
    
    Route::resource('students', \App\Http\Controllers\StudentController::class);
    Route::resource('projects', \App\Http\Controllers\ProjectController::class);
    Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class);
    Route::resource('galleries', \App\Http\Controllers\GalleryController::class)->except(['show']);
    Route::resource('schedules', \App\Http\Controllers\ScheduleController::class)->except(['show']);

    // Admin Hosting Management - can manage ALL hostings
    Route::get('/hostings', [\App\Http\Controllers\HostingController::class, 'index'])->name('hostings.index');
    Route::post('/hostings', [\App\Http\Controllers\HostingController::class, 'store'])->name('hostings.store');
    Route::get('/hostings/{hosting}', [\App\Http\Controllers\HostingController::class, 'show'])->name('hostings.show');
    Route::delete('/hostings/{hosting}', [\App\Http\Controllers\HostingController::class, 'destroy'])->name('hostings.destroy');
    Route::post('/hostings/{hosting}/toggle', [\App\Http\Controllers\HostingController::class, 'toggle'])->name('hostings.toggle');

    // Admin can access file manager for ANY hosting
    Route::get('/hostings/{hosting}/files', [\App\Http\Controllers\FileManagerController::class, 'index'])->name('hostings.files');
    Route::post('/hostings/{hosting}/files/upload', [\App\Http\Controllers\FileManagerController::class, 'upload'])->name('hostings.files.upload');
    Route::post('/hostings/{hosting}/files/mkdir', [\App\Http\Controllers\FileManagerController::class, 'mkdir'])->name('hostings.files.mkdir');
    Route::post('/hostings/{hosting}/files/mkfile', [\App\Http\Controllers\FileManagerController::class, 'mkfile'])->name('hostings.files.mkfile');
    Route::get('/hostings/{hosting}/files/edit', [\App\Http\Controllers\FileManagerController::class, 'edit'])->name('hostings.files.edit');
    Route::put('/hostings/{hosting}/files/edit', [\App\Http\Controllers\FileManagerController::class, 'update'])->name('hostings.files.update');
    Route::post('/hostings/{hosting}/files/rename', [\App\Http\Controllers\FileManagerController::class, 'rename'])->name('hostings.files.rename');
    Route::delete('/hostings/{hosting}/files', [\App\Http\Controllers\FileManagerController::class, 'destroy'])->name('hostings.files.destroy');
    Route::post('/hostings/{hosting}/files/bulk-delete', [\App\Http\Controllers\FileManagerController::class, 'bulkDelete'])->name('hostings.files.bulk-delete');
    Route::get('/hostings/{hosting}/files/download', [\App\Http\Controllers\FileManagerController::class, 'download'])->name('hostings.files.download');
    Route::post('/hostings/{hosting}/files/extract', [\App\Http\Controllers\FileManagerController::class, 'extract'])->name('hostings.files.extract');
    Route::post('/hostings/{hosting}/files/paste', [\App\Http\Controllers\FileManagerController::class, 'paste'])->name('hostings.files.paste');
});

// ============================================================
// MAHASISWA PANEL - Only for Students (student guard)
// Prefix: /mahasiswa
// ============================================================
Route::middleware(['auth:student'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // Dashboard - show dashboard view
    Route::get('/', function () {
        $student = \Illuminate\Support\Facades\Auth::guard('student')->user();
        return view('mahasiswa.dashboard', ['student' => $student]);
    })->name('dashboard');

    // Hosting Dashboard - redirect to own hosting file manager
    Route::get('/hosting', function () {
        $student = \Illuminate\Support\Facades\Auth::guard('student')->user();
        if (!$student->hosting) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Belum punya hosting.');
        }
        return redirect()->route('mahasiswa.hosting.files');
    })->name('hosting.dashboard');

    // File Manager - only own hosting
    Route::get('/hosting/files', [\App\Http\Controllers\FileManagerController::class, 'index'])->name('hosting.files');
    Route::post('/hosting/files/upload', [\App\Http\Controllers\FileManagerController::class, 'upload'])->name('hosting.files.upload');
    Route::post('/hosting/files/mkdir', [\App\Http\Controllers\FileManagerController::class, 'mkdir'])->name('hosting.files.mkdir');
    Route::post('/hosting/files/mkfile', [\App\Http\Controllers\FileManagerController::class, 'mkfile'])->name('hosting.files.mkfile');
    Route::get('/hosting/files/edit', [\App\Http\Controllers\FileManagerController::class, 'edit'])->name('hosting.files.edit');
    Route::put('/hosting/files/edit', [\App\Http\Controllers\FileManagerController::class, 'update'])->name('hosting.files.update');
    Route::post('/hosting/files/rename', [\App\Http\Controllers\FileManagerController::class, 'rename'])->name('hosting.files.rename');
    Route::delete('/hosting/files', [\App\Http\Controllers\FileManagerController::class, 'destroy'])->name('hosting.files.destroy');
    Route::post('/hosting/files/bulk-delete', [\App\Http\Controllers\FileManagerController::class, 'bulkDelete'])->name('hosting.files.bulk-delete');
    Route::get('/hosting/files/download', [\App\Http\Controllers\FileManagerController::class, 'download'])->name('hosting.files.download');
    Route::post('/hosting/files/extract', [\App\Http\Controllers\FileManagerController::class, 'extract'])->name('hosting.files.extract');
    Route::post('/hosting/files/paste', [\App\Http\Controllers\FileManagerController::class, 'paste'])->name('hosting.files.paste');

    // Hosting settings - only own hosting
    Route::get('/hosting/settings', function () {
        $student = \Illuminate\Support\Facades\Auth::guard('student')->user();
        if (!$student->hosting) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Belum punya hosting.');
        }
        return view('mahasiswa.hosting.settings', ['hosting' => $student->hosting]);
    })->name('hosting.settings');
    
    Route::put('/hosting/settings', function (\Illuminate\Http\Request $request) {
        $student = \Illuminate\Support\Facades\Auth::guard('student')->user();
        $hosting = $student->hosting;
        if (!$hosting) abort(404);
        
        $request->validate([
            'domain' => 'nullable|string|max:255|unique:hostings,domain,' . $hosting->id . '|regex:/^[a-z0-9\-\.]+$/',
        ]);
        
        $hosting->domain = $request->input('domain') ?? strtolower($student->nim) . '.d4rpl4b.test';
        $hosting->save();
        
        return back()->with('success', 'Domain diupdate.');
    })->name('hosting.settings.update');
});

// Public Hosting Serve (bisa diakses publik)
Route::get('/hosting/{hash}/{any?}', [\App\Http\Controllers\HostingServeController::class, 'show'])->where('any', '.*')->name('hosting.show');

// Portfolio Hosting - serve project portfolios
Route::get('/portfolio/{hash}/{any?}', [\App\Http\Controllers\PortfolioController::class, 'show'])->where('any', '.*')->name('portfolio.show');

// Public Landing Page
Route::get('/', function () {
    $students = Student::orderBy('nim')->get();
    $projects = Project::latest()->get();
    $announcements = Announcement::orderBy('event_date', 'asc')->get();
    $galleries = Gallery::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
    $schedules = Schedule::orderBy('sort_order')->orderBy('id')->get();

    return view('welcome', compact('students', 'projects', 'announcements', 'galleries', 'schedules'));
});

// Authentication Routes
Route::middleware('guest:web,student')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:web,student');