<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
            'role' => ['required','in:admin,pemilik,penyewa'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            // Ensure selected role matches the user's role
            if (Auth::user()->role !== $credentials['role']) {
                Auth::logout();
                return back()->withErrors(['email' => 'Role tidak sesuai dengan akun. Pilih role yang benar.'])->onlyInput('email');
            }
            // Redirect to role-specific dashboard with success message
            $dest = match ($credentials['role']) {
                'admin' => route('admin.dashboard'),
                'pemilik' => route('pemilik.dashboard'),
                default => route('penyewa.dashboard'),
            };
            return redirect($dest)->with('success', 'Login sukses! Selamat datang ' . Auth::user()->name);
        }

        return back()->withErrors(['email' => 'Kredensial tidak valid'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'phone' => ['nullable','string','max:50'],
            'password' => ['required','min:6','confirmed'],
            'role' => ['required','in:penyewa,pemilik'], // admin tidak melalui register
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        // Setelah registrasi, jangan auto-login. Arahkan kembali ke halaman login dengan pesan sukses.
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login untuk melanjutkan.');
    }

    public function logout(Request $request)
    {
        $userName = Auth::user()->name ?? 'User';
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logout berhasil! Sampai jumpa lagi, ' . $userName);
    }
}
