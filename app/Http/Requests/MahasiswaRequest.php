<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $mahasiswa = $this->route('mahasiswa');
        $id = $mahasiswa instanceof User ? $mahasiswa->id : null;

        return [
            'nama' => ['required', 'string', 'max:150'],
            'nim' => ['required', 'string', 'max:30', Rule::unique('users', 'nim')->ignore($id)],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users', 'username')->ignore($id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'password' => [$id ? 'nullable' : 'required', 'string', 'min:8'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'prodi' => ['required', 'string', Rule::in(User::PROGRAM_STUDI)],
            'angkatan' => ['required', 'integer', 'min:2000', 'max:'.now()->year],
        ];
    }
}
