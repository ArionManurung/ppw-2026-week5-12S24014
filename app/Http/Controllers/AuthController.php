<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    /**
     * Tampilkan formulir login pustakawan.
     */
    public function showLoginForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('buku.index');
        }

        // Ambil riwayat email pustakawan dari HTTP Cookie jika tersedia
        $rememberedEmail = $request->cookie('del_last_librarian_email');

        return view('auth.login', compact('rememberedEmail'));
    }

    /**
     * Proses otentikasi login pustakawan, regenerasi session ID, dan set cookie.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Alamat email pustakawan wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            // WAJIB: Regenerasi ID Sesi untuk menangkal serangan Session Fixation
            $request->session()->regenerate();

            // Simpan metadata sesi login
            session(['login_timestamp' => now()->format('d M Y, H:i:s') . ' WIB']);
            session(['librarian_name' => Auth::user()->name]);

            // Set HTTP Cookie persisten selama 7 hari (10.080 menit) dengan HttpOnly
            Cookie::queue('del_last_librarian_email', $request->email, 10080);

            return redirect()->intended(route('buku.index'))
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '! Sesi Pustakawan Anda telah aktif secara aman.');
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->with('error', 'Kredensial login tidak valid. Silakan periksa kembali email dan kata sandi Anda.');
    }

    /**
     * Logout pustakawan, invalidate session, dan regenerasi CSRF token.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate sesi di server dan regenerasi CSRF token anti-tampering
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Sesi server pustakawan telah dihapus dan token CSRF telah diregenerasi dengan aman.');
    }
}
