<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Models\Student;
use App\Models\Project;
use App\Models\Announcement;
use App\Models\Gallery;
use App\Models\Schedule;

// Hosting per Mahasiswa - serve like cPanel
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
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    Route::get('/hero', [\App\Http\Controllers\HeroSettingController::class, 'edit'])->name('hero.edit');
    Route::put('/hero', [\App\Http\Controllers\HeroSettingController::class, 'update'])->name('hero.update');

    Route::resource('students', \App\Http\Controllers\StudentController::class);
    Route::resource('projects', \App\Http\Controllers\ProjectController::class);
    Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class);
    Route::resource('galleries', \App\Http\Controllers\GalleryController::class)->except(['show']);
    Route::resource('schedules', \App\Http\Controllers\ScheduleController::class)->except(['show']);

    // Hosting per Mahasiswa - cPanel like
    Route::get('/hostings', [\App\Http\Controllers\HostingController::class, 'index'])->name('hostings.index');
    Route::post('/hostings', [\App\Http\Controllers\HostingController::class, 'store'])->name('hostings.store');
    Route::get('/hostings/{hosting}', [\App\Http\Controllers\HostingController::class, 'show'])->name('hostings.show');
    Route::delete('/hostings/{hosting}', [\App\Http\Controllers\HostingController::class, 'destroy'])->name('hostings.destroy');
    Route::post('/hostings/{hosting}/toggle', [\App\Http\Controllers\HostingController::class, 'toggle'])->name('hostings.toggle');

    Route::get('/hostings/{hosting}/files', [\App\Http\Controllers\FileManagerController::class, 'index'])->name('hostings.files');
    Route::post('/hostings/{hosting}/files/upload', [\App\Http\Controllers\FileManagerController::class, 'upload'])->name('hostings.files.upload');
    Route::post('/hostings/{hosting}/files/mkdir', [\App\Http\Controllers\FileManagerController::class, 'mkdir'])->name('hostings.files.mkdir');
    Route::post('/hostings/{hosting}/files/mkfile', [\App\Http\Controllers\FileManagerController::class, 'mkfile'])->name('hostings.files.mkfile');
    Route::get('/hostings/{hosting}/files/edit', [\App\Http\Controllers\FileManagerController::class, 'edit'])->name('hostings.files.edit');
    Route::put('/hostings/{hosting}/files/edit', [\App\Http\Controllers\FileManagerController::class, 'update'])->name('hostings.files.update');
    Route::post('/hostings/{hosting}/files/rename', [\App\Http\Controllers\FileManagerController::class, 'rename'])->name('hostings.files.rename');
    Route::delete('/hostings/{hosting}/files', [\App\Http\Controllers\FileManagerController::class, 'destroy'])->name('hostings.files.destroy');
    Route::get('/hostings/{hosting}/files/download', [\App\Http\Controllers\FileManagerController::class, 'download'])->name('hostings.files.download');
});
