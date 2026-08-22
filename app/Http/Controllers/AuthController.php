<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nim' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string'],
        ]);

        $nim = trim($request->input('nim'));
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // 1. Coba login sebagai Student (tabel students) — NIM = password = NIM
        if (Auth::guard('student')->attempt(['nim' => $nim, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('mahasiswa.dashboard'));
        }

        // 2. Coba sebagai admin: jika input mengandung '@' gunakan email, atau coba email langsung
        $emailAttempt = str_contains($nim, '@') ? $nim : null;
        if ($emailAttempt && Auth::guard('web')->attempt(['email' => $emailAttempt, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            return $this->redirectByRoleWeb();
        }

        // 3. Fallback: coba nim sebagai email (nim@polindra.ac.id) untuk admin
        if (!$emailAttempt) {
            $emailGuess = strtolower($nim) . '@polindra.ac.id';
            if (Auth::guard('web')->attempt(['email' => $emailGuess, 'password' => $password], $remember)) {
                $request->session()->regenerate();
                return $this->redirectByRoleWeb();
            }
        }

        return back()->withErrors([
            'nim' => 'NIM atau password salah. Mahasiswa: gunakan NIM sebagai password.',
        ])->onlyInput('nim');
    }

    private function redirectByRoleWeb()
    {
        $user = Auth::guard('web')->user();
        if ($user && $user->role === 'admin') {
            return redirect()->intended('/admin/dashboard');
        }
        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        // Logout both guards
        Auth::guard('web')->logout();
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
