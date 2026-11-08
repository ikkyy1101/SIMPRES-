<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $login = $data['login'];

        $user = User::query()
            ->where(function ($query) use ($login) {
                $query->where('username', $login)
                    ->orWhere('email', $login)
                    ->orWhere('nim', $login);
            })
            ->where('role', $data['role'])
            ->first();

        if (
            ! $user ||
            ! Auth::attempt([
                'username' => $user->username,
                'password' => $data['password'],
            ])
        ) {
            return back()
                ->withErrors([
                    'login' => 'Username, email, NIM, password, atau role salah.',
                ])
                ->onlyInput('login', 'role');
        }

        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isPimpinan()) {
            return redirect()->route('pimpinan.dashboard');
        }

        return redirect()->route('mahasiswa.dashboard');
    }

    /**
     * Fitur Login Cepat (Uji Coba Demo Akun) untuk Mahasiswa, Admin, dan Pemimpin.
     */
    public function quickLogin(Request $request, string $role): RedirectResponse
    {
        $validRoles = ['mahasiswa', 'admin', 'pimpinan'];
        if (! in_array($role, $validRoles, true)) {
            return redirect()->route('login');
        }

        $user = User::query()->where('role', $role)->first();

        if (! $user) {
            return redirect()->route('login')->withErrors([
                'login' => 'Akun demo untuk peran '.ucfirst($role).' belum tersedia di database.',
            ]);
        }

        Auth::login($user, (bool) $request->boolean('remember'));
        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isPimpinan()) {
            return redirect()->route('pimpinan.dashboard');
        }

        return redirect()->route('mahasiswa.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
