<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesertaLoginController extends Controller
{
    /**
     * Tampilkan form login peserta
     */
    public function showLoginForm()
    {
        // Jika sudah login (sebagai peserta), redirect ke dashboard peserta
        if (Auth::guard('peserta')->check()) {
            return redirect()->route('peserta.dashboard');
        }
        return view('auth.peserta-login');
    }

    /**
     * Handle proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Coba login menggunakan guard 'peserta'
        // Kita cek HANYA yang statusnya 'true' (aktif)
        if (Auth::guard('peserta')->attempt(array_merge($credentials, ['status' => true]), $request->filled('remember'))) {

            $request->session()->regenerate();

            // Redirect ke dashboard peserta
            return redirect()->intended(route('peserta.dashboard'));
        }

        // Jika gagal
        return back()->withErrors([
            'username' => 'Username atau Password salah.',
        ])->onlyInput('username');
    }

    /**
     * Handle proses logout
     */
    public function logout(Request $request)
    {
        Auth::guard('peserta')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Redirect ke halaman utama
    }
}
