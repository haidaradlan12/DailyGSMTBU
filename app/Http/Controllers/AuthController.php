<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Email atau kata sandi yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        if (! $user->isApproved()) {
            return back()->withErrors([
                'email' => 'Akun Anda belum divalidasi oleh Admin Utama. Mohon menunggu persetujuan.',
            ])->onlyInput('email');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'))
            ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'operator',
            'is_approved' => false, // Harus divalidasi oleh admin utama
        ]);

        return redirect()->route('login')->with(
            'success',
            'Pendaftaran berhasil! Akun Anda telah dibuat dan sedang menunggu validasi oleh Admin Utama sebelum dapat digunakan untuk login.'
        );
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard')->with('success', 'Anda telah berhasil keluar.');
    }
}
