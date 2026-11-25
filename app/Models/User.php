<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const PROGRAM_STUDI = [
        'Sistem Informasi',
        'Ilmu Aktuaria',
        'Matematika',
    ];

    protected $fillable = [
        'nama',
        'nim',
        'username',
        'email',
        'password',
        'no_hp',
        'prodi',
        'fakultas',
        'angkatan',
        'foto_profil',
        'gender',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'angkatan' => 'integer',
        ];
    }

    public function prestasi(): HasMany
    {
        return $this->hasMany(
            Prestasi::class,
            'id_mahasiswa'
        );
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'id_user');
    }

    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class, 'id_user');
    }

    public function pimpinan(): HasOne
    {
        return $this->hasOne(Pimpinan::class, 'id_user');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }
}
