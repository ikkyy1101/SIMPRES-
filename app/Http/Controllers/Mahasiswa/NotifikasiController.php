<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /** Notifikasi status verifikasi (F-42). */
    public function index(Request $request): View
    {
        $notifikasi = $request->user()
            ->notifikasi()
            ->with('prestasi:id,nama_prestasi,status_verifikasi')
            ->latest()
            ->paginate(15);

        return view('mahasiswa.notifikasi.index', compact('notifikasi'));
    }

    public function bacaSemua(Request $request): RedirectResponse
    {
        $request->user()
            ->notifikasi()
            ->whereNull('dibaca_pada')
            ->update(['dibaca_pada' => now()]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
