<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

/**
 * AuthController
 *
 * Controller yang bertanggung jawab untuk menangani autentikasi
 * pengguna termasuk login dan logout.
 *
 * @author Library Management Team
 * @version 1.0
 */
class AuthController extends Controller
{
    /**
     * Handle login form submission
     *
     * Memproses login pengguna berdasarkan role (admin/member)
     * dan mengarahkan ke dashboard yang sesuai.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|max:20|regex:/^[a-zA-Z][a-zA-Z0-9_-]*$/',
            'password' => 'required|string|min:8|max:20',
            'role' => 'required|in:admin,member',
        ]);

        // Cari user berdasarkan username dan role
        $user = User::where('username', $request->username)
                   ->where('role', $request->role)
                   ->first();

        if ($user && password_verify($request->password, $user->password)) {
            // Login berhasil, simpan user ke session
            Auth::login($user);

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->route('dashboard')->with('success', 'Login berhasil sebagai Admin');
            } else {
                return redirect()->route('dashboard')->with('success', 'Login berhasil sebagai Member');
            }
        }

        // Login gagal
        return back()->withErrors(['login' => 'Username, password, atau role salah'])->withInput();
    }

    /**
     * Handle register form submission
     *
     * Memproses registrasi pengguna baru dengan role admin/member.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|max:20|regex:/^[a-zA-Z][a-zA-Z0-9_-]*$/|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:20',
            'role' => 'required|in:admin,member',
        ]);

        // Buat user baru
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => password_hash($request->password, PASSWORD_DEFAULT),
            'role' => $request->role,
        ]);

        // Login otomatis setelah registrasi
        Auth::login($user);

        // Redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->route('dashboard')->with('success', 'Registrasi berhasil sebagai Admin');
        } else {
            return redirect()->route('dashboard')->with('success', 'Registrasi berhasil sebagai Member');
        }
    }

    /**
     * Handle logout
     *
     * Keluarkan pengguna dari sistem.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home')->with('success', 'Logout berhasil');
    }
}
