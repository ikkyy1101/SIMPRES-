<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'mahasiswa')
            ->where(function ($query) {
                $query->whereNull('prodi')
                    ->orWhereNotIn('prodi', ['Sistem Informasi', 'Ilmu Aktuaria', 'Matematika']);
            })
            ->update(['prodi' => 'Sistem Informasi']);
    }

    public function down(): void {}
};
