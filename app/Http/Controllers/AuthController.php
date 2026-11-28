<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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

    public function showRegister(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('visited_landing')) {
            return redirect()->route('landing');
        }

        return view('auth.register', [
            'prodiList' => User::PROGRAM_STUDI,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'nim' => ['required', 'string', 'max:30', Rule::unique('users', 'nim')],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users', 'username')],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'prodi' => ['required', 'string', Rule::in(User::PROGRAM_STUDI)],
            'angkatan' => ['required', 'integer', 'min:2000', 'max:'.(date('Y') + 1)],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'nama' => $data['nama'],
            'nim' => $data['nim'],
            'username' => $data['username'],
            'email' => $data['email'],
            'prodi' => $data['prodi'],
            'fakultas' => 'Fakultas Sains dan Teknologi',
            'angkatan' => $data['angkatan'],
            'no_hp' => $data['no_hp'] ?? null,
            'password' => $data['password'],
            'role' => 'mahasiswa',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('mahasiswa.dashboard')
            ->with('success', 'Pendaftaran akun berhasil. Selamat datang di SIMPRES!');
    }

    public function showResetPassword(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('visited_landing')) {
            return redirect()->route('landing');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(['mahasiswa', 'admin', 'pimpinan'])],
            'login' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $login = $data['login'];

        $user = User::query()
            ->where('role', $data['role'])
            ->where('email', $data['email'])
            ->where(function ($query) use ($login) {
                $query->where('username', $login)
                    ->orWhere('nim', $login);
            })
            ->first();

        if (! $user) {
            return back()
                ->withErrors([
                    'login' => 'Data akun (peran, email, dan username/NIM) tidak cocok atau tidak ditemukan.',
                ])
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        $user->update([
            'password' => $data['password'],
        ]);

        return redirect()
            ->route('login')
            ->with('status', 'Kata sandi berhasil diperbarui. Silakan login menggunakan kata sandi baru Anda.');
    }

    public function redirectToGoogle(Request $request): RedirectResponse
    {
        if (! $request->session()->has('visited_landing')) {
            return redirect()->route('landing');
        }

        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');

        if (empty($clientId) || empty($clientSecret)) {
            return redirect()->route('login')->withErrors([
                'login' => 'Login Google belum dikonfigurasi. Isi GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET pada file .env.',
            ]);
        }

        $state = Str::random(40);
        $request->session()->put('google_oauth_state', $state);

        $redirectUri = $this->googleRedirectUri();

        $query = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'prompt' => 'select_account',
        ]);

        return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?'.$query);
    }

    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        $expectedState = $request->session()->pull('google_oauth_state');
        $state = $request->query('state');

        if (! $expectedState || ! is_string($state) || ! hash_equals($expectedState, $state)) {
            return redirect()->route('login')->withErrors([
                'login' => 'Sesi login Google tidak valid atau telah kedaluwarsa. Silakan coba lagi.',
            ]);
        }

        if ($request->filled('error') || ! $request->filled('code')) {
            return redirect()->route('login')->withErrors([
                'login' => 'Autentikasi Google dibatalkan atau gagal.',
            ]);
        }

        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'code' => $request->query('code'),
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->googleRedirectUri(),
        ]);

        if (! $tokenResponse->successful() || ! $tokenResponse->json('access_token')) {
            return redirect()->route('login')->withErrors([
                'login' => 'Gagal memverifikasi token Google. Periksa konfigurasi Client ID & Secret.',
            ]);
        }

        $profileResponse = Http::withToken($tokenResponse->json('access_token'))
            ->get('https://www.googleapis.com/oauth2/v3/userinfo');

        $email = $profileResponse->json('email');
        $nama = $profileResponse->json('name') ?: 'Mahasiswa SIMPRES';

        if (! $profileResponse->successful() || ! is_string($email) || $email === '') {
            return redirect()->route('login')->withErrors([
                'login' => 'Tidak dapat mengambil alamat email dari akun Google Anda.',
            ]);
        }

        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            $baseUsername = Str::slug(Str::before($email, '@'), '_') ?: 'mhs';
            $baseUsername = substr($baseUsername, 0, 35);
            $username = $baseUsername;
            $suffix = 1;

            while (User::query()->where('username', $username)->exists()) {
                $username = $baseUsername.'_'.$suffix;
                $suffix++;
            }

            $user = User::create([
                'nama' => $nama,
                'username' => $username,
                'email' => $email,
                'password' => Str::random(24),
                'fakultas' => 'Fakultas Sains dan Teknologi',
                'role' => 'mahasiswa',
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->put('visited_landing', true);

        return redirect()->route('login');
    }

    private function googleRedirectUri(): string
    {
        $configured = (string) config('services.google.redirect', '/auth/google/callback');

        return Str::startsWith($configured, ['http://', 'https://'])
            ? $configured
            : url($configured);
    }

    private function redirectByRole(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isPimpinan()) {
            return redirect()->route('pimpinan.dashboard');
        }

        return redirect()->route('mahasiswa.dashboard');
    }
}
