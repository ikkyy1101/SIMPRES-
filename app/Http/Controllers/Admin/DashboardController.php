<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->input('q');

        $baseQuery = Prestasi::query()
            ->when($q, function ($query, $search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('nama_prestasi', 'like', "%{$search}%")
                        ->orWhere('penyelenggara', 'like', "%{$search}%")
                        ->orWhereHas('mahasiswa', function ($m) use ($search) {
                            $m->where('nama', 'like', "%{$search}%")
                                ->orWhere('nim', 'like', "%{$search}%");
                        });
                });
            });

        // 4 Stat Cards sesuai Lap. hal. 115-116:
        $totalPrestasi = (clone $baseQuery)->count();
        $mahasiswaBerprestasi = (clone $baseQuery)
            ->select('prestasi.id_mahasiswa')
            ->distinct()
            ->get()
            ->count();
        $dalamProses = (clone $baseQuery)->where('status_verifikasi', 'menunggu')->count();
        $perluPerbaikan = (clone $baseQuery)->where('status_verifikasi', 'perlu_perbaikan')->count();
        $terverifikasi = (clone $baseQuery)->where('status_verifikasi', 'terverifikasi')->count();

        // 1. Donut Chart: Prestasi per Kategori
        $kategoriList = Prestasi::JENIS;
        $kategoriCounts = [];
        $totalForKategori = (clone $baseQuery)->count();
        foreach ($kategoriList as $key => $label) {
            $cnt = (clone $baseQuery)->where('jenis_prestasi', $key)->count();
            $kategoriCounts[$label] = [
                'count' => $cnt,
                'pct' => $totalForKategori > 0 ? \round(($cnt / $totalForKategori) * 100, 1) : 0,
            ];
        }

        // 2. Donut Chart: Status Verifikasi
        $statusCounts = [
            'Terverifikasi' => [
                'count' => $terverifikasi,
                'pct' => $totalPrestasi > 0 ? \round(($terverifikasi / $totalPrestasi) * 100, 1) : 0,
                'color' => '#5c6742',
            ],
            'Menunggu Verifikasi' => [
                'count' => $dalamProses,
                'pct' => $totalPrestasi > 0 ? \round(($dalamProses / $totalPrestasi) * 100, 1) : 0,
                'color' => '#d4a373',
            ],
            'Perlu Perbaikan' => [
                'count' => $perluPerbaikan,
                'pct' => $totalPrestasi > 0 ? \round(($perluPerbaikan / $totalPrestasi) * 100, 1) : 0,
                'color' => '#b23a48',
            ],
        ];

        // 5 Prestasi terbaru yang menunggu verifikasi
        $menungguTerbaru = (clone $baseQuery)
            ->with('mahasiswa:id,nama,nim,prodi')
            ->where('status_verifikasi', 'menunggu')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPrestasi',
            'mahasiswaBerprestasi',
            'dalamProses',
            'perluPerbaikan',
            'terverifikasi',
            'kategoriCounts',
            'statusCounts',
            'menungguTerbaru',
            'q'
        ));
    }

    /** Rekapitulasi Data Prestasi Admin (Lap. hal. 123-124) */
    /**
     * Kriteria pengelompokan rekap (F-29, F-30). Ekspresi SQL bersifat tetap (whitelist).
     */
    public function rekapitulasi(Request $request): View|StreamedResponse
    {
        $kategori = $request->input('kategori');
        $tingkat = $request->input('tingkat');
        $tahun = $request->input('tahun') ?? $request->input('tahun_perolehan');
        $prodi = $request->input('prodi');
        $q = $request->input('q');

        $query = Prestasi::query()
            ->with(['mahasiswa'])
            ->join('users', 'users.id', '=', 'prestasi.id_mahasiswa')
            ->select('prestasi.*')
            ->when($kategori, fn ($b) => $b->where('prestasi.jenis_prestasi', $kategori))
            ->when($tingkat, fn ($b) => $b->where('prestasi.tingkat_prestasi', $tingkat))
            ->when($tahun, fn ($b) => $b->where('prestasi.tahun_perolehan', $tahun))
            ->when($prodi, fn ($b) => $b->where('users.prodi', $prodi))
            ->when($q, function ($b) use ($q) {
                $b->where(function ($sub) use ($q) {
                    $sub->where('prestasi.nama_prestasi', 'like', "%{$q}%")
                        ->orWhere('users.nama', 'like', "%{$q}%")
                        ->orWhere('users.nim', 'like', "%{$q}%");
                });
            });

        // Export Excel
        if ($request->input('export') === 'excel') {
            $data = (clone $query)->orderByDesc('prestasi.tahun_perolehan')->get();

            return response()->streamDownload(function () use ($data) {
                $out = fopen('php://output', 'w');
                fwrite($out, "\xEF\xBB\xBF");
                fputcsv($out, ['No', 'Nama Mahasiswa', 'NIM', 'Program Studi', 'Nama Prestasi', 'Kategori', 'Tingkat', 'Tahun', 'Hasil/Perolehan', 'Skor', 'Status']);

                foreach ($data as $i => $item) {
                    fputcsv($out, [
                        $i + 1,
                        $item->mahasiswa?->nama,
                        $item->mahasiswa?->nim,
                        $item->mahasiswa?->prodi,
                        $item->nama_prestasi,
                        Prestasi::JENIS[$item->jenis_prestasi] ?? $item->jenis_prestasi,
                        Prestasi::TINGKAT[$item->tingkat_prestasi] ?? $item->tingkat_prestasi,
                        $item->tahun_perolehan,
                        Prestasi::PEROLEHAN[$item->perolehan] ?? $item->perolehan,
                        $item->skor ?? '-',
                        Prestasi::STATUS[$item->status_verifikasi] ?? $item->status_verifikasi,
                    ]);
                }
                fclose($out);
            }, 'rekapitulasi_prestasi_admin_'.date('Ymd_His').'.csv', [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]);
        }

        // Charts
        $totalAll = (clone $query)->reorder()->count('prestasi.id');
        $kategoriPersentase = [];
        foreach (Prestasi::JENIS as $kKey => $kLabel) {
            $count = (clone $query)->reorder()->where('prestasi.jenis_prestasi', $kKey)->count('prestasi.id');
            $percent = $totalAll > 0 ? \round(($count / $totalAll) * 100, 1) : 0;
            $kategoriPersentase[$kLabel] = [
                'count' => $count,
                'percent' => $percent,
            ];
        }

        $rekapPerTahun = (clone $query)
            ->reorder()
            ->select('prestasi.tahun_perolehan')
            ->selectRaw('COUNT(DISTINCT prestasi.id) as total')
            ->groupBy('prestasi.tahun_perolehan')
            ->orderBy('prestasi.tahun_perolehan')
            ->pluck('total', 'prestasi.tahun_perolehan')
            ->toArray();

        $prestasiList = $query->orderByDesc('prestasi.created_at')->paginate(15)->withQueryString();

        $prodiList = User::query()
            ->whereNotNull('prodi')
            ->distinct()
            ->orderBy('prodi')
            ->pluck('prodi');

        $tahunList = Prestasi::query()
            ->distinct()
            ->orderByDesc('tahun_perolehan')
            ->pluck('tahun_perolehan');

        return view('admin.rekapitulasi', compact(
            'prestasiList',
            'kategoriPersentase',
            'totalAll',
            'kategori',
            'tingkat',
            'tahun',
            'prodi',
            'q',
            'prodiList',
            'tahunList',
            'rekapPerTahun'
        ));
    }

    /** Halaman Riwayat Data Prestasi Admin (Lap. hal. 124-125) */
    public function riwayat(Request $request): View|StreamedResponse
    {
        $kategori = $request->input('kategori');
        $tingkat = $request->input('tingkat');
        $tahun = $request->input('tahun') ?? $request->input('tahun_perolehan');
        $status = $request->input('status');
        $q = $request->input('q');

        $query = Prestasi::query()
            ->with(['mahasiswa'])
            ->join('users', 'users.id', '=', 'prestasi.id_mahasiswa')
            ->select('prestasi.*')
            ->when($kategori, fn ($b) => $b->where('prestasi.jenis_prestasi', $kategori))
            ->when($tingkat, fn ($b) => $b->where('prestasi.tingkat_prestasi', $tingkat))
            ->when($tahun, fn ($b) => $b->where('prestasi.tahun_perolehan', $tahun))
            ->when($status, fn ($b) => $b->where('prestasi.status_verifikasi', $status))
            ->when($q, function ($b) use ($q) {
                $b->where(function ($sub) use ($q) {
                    $sub->where('prestasi.nama_prestasi', 'like', "%{$q}%")
                        ->orWhere('users.nama', 'like', "%{$q}%")
                        ->orWhere('users.nim', 'like', "%{$q}%");
                });
            });

        // Export Excel
        if ($request->input('export') === 'excel') {
            $data = (clone $query)->orderByDesc('prestasi.created_at')->get();

            return response()->streamDownload(function () use ($data) {
                $out = fopen('php://output', 'w');
                fwrite($out, "\xEF\xBB\xBF");
                fputcsv($out, ['No', 'Nama Mahasiswa', 'NIM', 'Nama Prestasi', 'Kategori', 'Tingkat', 'Tahun', 'Status Verifikasi', 'Skor']);

                foreach ($data as $i => $item) {
                    fputcsv($out, [
                        $i + 1,
                        $item->mahasiswa?->nama,
                        $item->mahasiswa?->nim,
                        $item->nama_prestasi,
                        Prestasi::JENIS[$item->jenis_prestasi] ?? $item->jenis_prestasi,
                        Prestasi::TINGKAT[$item->tingkat_prestasi] ?? $item->tingkat_prestasi,
                        $item->tahun_perolehan,
                        Prestasi::STATUS[$item->status_verifikasi] ?? $item->status_verifikasi,
                        $item->skor ?? '-',
                    ]);
                }
                fclose($out);
            }, 'riwayat_prestasi_admin_'.date('Ymd_His').'.csv', [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]);
        }

        $prestasi = $query->orderByDesc('prestasi.created_at')->paginate(15)->withQueryString();

        $tahunList = Prestasi::query()
            ->distinct()
            ->orderByDesc('tahun_perolehan')
            ->pluck('tahun_perolehan');

        return view('admin.riwayat', compact(
            'prestasi',
            'kategori',
            'tingkat',
            'tahun',
            'status',
            'q',
            'tahunList'
        ));
    }
}
