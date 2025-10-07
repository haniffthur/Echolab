<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman form login.
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Tampilkan view yang sudah kita buat
    }

    /**
     * Menangani proses login.
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // 2. Siapkan data untuk percobaan login
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        // 3. Lakukan percobaan login
        if (Auth::attempt($credentials, $remember)) {
            // Jika berhasil, regenerate session & arahkan ke dashboard
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // 4. Jika gagal, kembalikan ke form login dengan pesan error
        throw ValidationException::withMessages([
            'email' => [__('auth.failed')],
        ]);
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}