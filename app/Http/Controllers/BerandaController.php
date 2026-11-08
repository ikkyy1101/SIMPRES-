<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;

class BerandaController extends Controller
{
    /**
     * Menampilkan Halaman Beranda / Landing Page (Lap. hal. 100-101)
     */
    public function index(): View
    {
        $totalPrestasi = 0;
        $mahasiswaBerprestasi = 0;
        $kategoriCount = count(Prestasi::JENIS);
        $prestasiTerbaru = collect();

        if (Schema::hasTable('prestasi')) {
            $totalPrestasi = Prestasi::query()
                ->where('status_verifikasi', 'terverifikasi')
                ->count();

            $mahasiswaBerprestasi = Prestasi::query()
                ->where('status_verifikasi', 'terverifikasi')
                ->distinct('id_mahasiswa')
                ->count('id_mahasiswa');

            $dbKategoriCount = Prestasi::query()
                ->where('status_verifikasi', 'terverifikasi')
                ->distinct('jenis_prestasi')
                ->count('jenis_prestasi');

            if ($dbKategoriCount > 0) {
                $kategoriCount = $dbKategoriCount;
            }

            $prestasiTerbaru = Prestasi::query()
                ->with('mahasiswa:id,nama,prodi,nim')
                ->where('status_verifikasi', 'terverifikasi')
                ->latest('tahun_perolehan')
                ->latest('id')
                ->limit(6)
                ->get();
        }

        return view('welcome', compact(
            'totalPrestasi',
            'mahasiswaBerprestasi',
            'kategoriCount',
            'prestasiTerbaru'
        ));
    }
}
