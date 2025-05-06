<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Method untuk menampilkan halaman login
    public function loginBackend()
    {
        return view('backend.v_login.login', [
            'judul' => 'Login',
        ]);
    }

    // Method untuk autentikasi user
    public function authenticateBackend(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Silakan masukkan alamat email Anda.',
            'password.required' => 'Silakan masukkan kata sandi Anda.'
        ]);

        // Cek kredensial
        if (Auth::attempt($credentials)) {
            // Cek status user
            if (Auth::user()->status == 0) {
                Auth::logout();
                return back()->with('error', 'User belum aktif');
            }

            // Regenerate session jika login berhasil
            $request->session()->regenerate();

            return redirect()->intended(route('backend.beranda'));
        }

        // Jika login gagal
        return back()->with('error', 'Login Gagal');
    }

    // Method untuk logout
    public function logoutBackend()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect(route('backend.login'));
    }
}