<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Models\Student;
use App\Models\Project;
use App\Models\Announcement;
use App\Models\Gallery;
use App\Models\Schedule;

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
});
