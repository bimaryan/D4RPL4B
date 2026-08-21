<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Project;
use App\Models\Announcement;
use App\Models\Gallery;
use App\Models\Schedule;

class AdminController extends Controller
{
    public function dashboard()
    {
        $studentsCount = Student::count();
        $projectsCount = Project::count();
        $announcementsCount = Announcement::count();
        $galleriesCount = Gallery::count();
        $schedulesCount = Schedule::count();
        $recentStudents = Student::orderBy('created_at','desc')->limit(5)->get();
        $recentProjects = Project::latest()->limit(5)->get();
        $recentAnnouncements = Announcement::orderBy('event_date','desc')->limit(5)->get();
        $recentGalleries = Gallery::orderBy('created_at','desc')->limit(5)->get();

        return view('admin.dashboard', compact('studentsCount', 'projectsCount', 'announcementsCount','galleriesCount','schedulesCount','recentStudents','recentProjects','recentAnnouncements','recentGalleries'));
    }
}
