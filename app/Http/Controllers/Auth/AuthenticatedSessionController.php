<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        // Validasi input awal
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');

        // Tentukan field otentikasi
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } elseif (preg_match('/^[0-9]+$/', $login)) {
            $field = 'nip';
        } else {
            $field = 'name';
        }

        $credentials = [
            $field     => $login,
            'password' => $request->password,
        ];

        // Coba proses otentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            
            // Jika data benar tapi akun non-aktif
            if (!Auth::user()->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Balasan khusus untuk AJAX
                if ($request->expectsJson()) {
                    return response()->json([
                        'errors' => ['login' => ['Akun Anda tidak aktif. Hubungi admin.']]
                    ], 422);
                }

                return back()->withErrors([
                    'login' => 'Akun Anda tidak aktif. Hubungi admin.',
                ])->onlyInput('login');
            }

            // Sukses login & akun aktif
            $request->session()->regenerate();
            
            // Balasan khusus untuk AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('dashboard')
                ]);
            }

            return redirect()->intended(route('dashboard'));
        }

        // Jika salah input email/nip/password
        if ($request->expectsJson()) {
            return response()->json([
                'errors' => ['login' => ['nama / nip / email atau password salah']]
            ], 422);
        }

        return back()->withErrors([
            'login' => 'nama / nip / email atau password salah',
        ])->onlyInput('login');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}