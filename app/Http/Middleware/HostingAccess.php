<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HostingAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $isAdmin = Auth::guard('web')->check() && Auth::guard('web')->user()->role === 'admin';
        $isStudent = Auth::guard('student')->check();

        if (!$isAdmin && !$isStudent) {
            return redirect('/login');
        }

        // Jika route ada parameter hosting, cek kepemilikan untuk mahasiswa
        $hosting = $request->route('hosting');
        if ($hosting && $isStudent && !$isAdmin) {
            $student = Auth::guard('student')->user();
            // hosting is resolved via hash, check student_id
            if ($hosting->student_id !== $student->id) {
                abort(403, 'Akses hosting ditolak. Kamu hanya bisa akses hosting milikmu.');
            }
        }

        // Untuk index hosting, mahasiswa hanya boleh lihat miliknya (akan difilter di controller)
        return $next($request);
    }
}
