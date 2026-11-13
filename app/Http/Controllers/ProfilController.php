<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Pimpinan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    /** Profil & akun pengguna (F-02, F-16). */
    public function show(Request $request): View
    {
        $user = $request->user()->load(['admin', 'pimpinan']);

        return view('profil.show', compact('user'));
    }

    /** Form edit profil sesuai halaman 106 & 118 laporan */
    public function edit(Request $request): View
    {
        $user = $request->user()->load(['admin', 'pimpinan']);

        return view('profil.edit', compact('user'));
    }

    /** Simpan pembaruan profil */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $rules = [
            'nama' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'gender' => ['nullable', 'string', 'in:Laki-laki,Perempuan'],
            'no_hp' => ['nullable', 'string', 'max:30'],
        ];

        if ($user->isMahasiswa()) {
            $rules['nim'] = ['required', 'string', 'max:30', Rule::unique('users')->ignore($user->id)];
            $rules['prodi'] = [
                'nullable',
                'string',
                Rule::in(['Sistem Informasi', 'Matematika', 'Ilmu Aktuaria']),
            ];
            $rules['fakultas'] = ['nullable', 'string', 'max:100'];
            $rules['angkatan'] = ['nullable', 'integer', 'min:2000', 'max:'.(date('Y') + 1)];
        } else {
            $rules['username'] = ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)];
        }

        $validated = $request->validate($rules);

        $user->update($validated);

        if ($user->isAdmin()) {
            Admin::updateOrCreate(
                ['id_user' => $user->id],
                [
                    'nama' => $user->nama,
                    'username' => $user->username,
                    'email' => $user->email,
                    'no_hp' => $user->no_hp,
                ]
            );
        } elseif ($user->isPimpinan()) {
            Pimpinan::updateOrCreate(
                ['id_user' => $user->id],
                [
                    'nama' => $user->nama,
                    'username' => $user->username,
                    'email' => $user->email,
                    'no_hp' => $user->no_hp,
                    'gender' => $user->gender,
                ]
            );
        }

        return redirect()->route('profil.show')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'password_lama' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update(['password' => $data['password']]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
