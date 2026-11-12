<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Pengguna;

class AuthController extends Controller
{
    // Tampilkan Halaman Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Arahkan berdasarkan role masing-masing secara langsung
            if ($user->role === 'admin') {
                return redirect()->to('/admin/dashboard');
            } elseif ($user->role === 'petugas') {
                return redirect()->to('/petugas/dashboard');
            } elseif ($user->role === 'pengguna') {
                return redirect()->to('/pengguna/dashboard');
            }

            Auth::logout();
            return redirect('/login')->withErrors(['email' => 'Role akun tidak dikenali sistem.']);
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Tampilkan Halaman Registrasi Pengguna
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses Registrasi Pengguna
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'nik' => 'nullable|string|max:16|unique:pengguna,nik',
            'no_hp' => 'nullable|string|max:15',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pengguna',
            ]);

            Pengguna::create([
                'user_id' => $user->id,
                'nama_pengguna' => $request->nama,
                'nik' => $request->nik,
                'no_hp' => $request->no_hp,
            ]);
        });

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login menggunakan akun Anda.');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
}