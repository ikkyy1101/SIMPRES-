<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $totalPrestasi = (clone $baseQuery)->count();
        $mahasiswaBerprestasi = (clone $baseQuery)
            ->select('prestasi.id_mahasiswa')
            ->distinct()
            ->get()
            ->count();
        $kategoriCount = (clone $baseQuery)->distinct()->count('jenis_prestasi');
        if ($kategoriCount === 0) {
            $kategoriCount = \count(Prestasi::JENIS);
        }
        $terverifikasi = (clone $baseQuery)->where('status_verifikasi', 'terverifikasi')->count();

        // 1. Kategori Prestasi (Horizontal bar chart data)
        $kategoriLabels = Prestasi::JENIS;
        $kategoriData = [];
        foreach ($kategoriLabels as $key => $label) {
            $kategoriData[$label] = (clone $baseQuery)->where('jenis_prestasi', $key)->count();
        }

        // 2. Tingkat Prestasi (Donut chart data)
        $tingkatLabels = Prestasi::TINGKAT;
        $tingkatData = [];
        foreach ($tingkatLabels as $key => $label) {
            $tingkatData[$label] = (clone $baseQuery)->where('tingkat_prestasi', $key)->count();
        }

        // 3. Tahun Perolehan (Bar chart per year)
        $tahunData = Prestasi::query()
            ->selectRaw('prestasi.tahun_perolehan, COUNT(DISTINCT prestasi.id) as total')
            ->groupBy('prestasi.tahun_perolehan')
            ->orderBy('prestasi.tahun_perolehan')
            ->pluck('total', 'prestasi.tahun_perolehan')
            ->toArray();

        // Default empty years if few
        if (empty($tahunData)) {
            $currYear = (int) date('Y');
            for ($y = $currYear - 4; $y <= $currYear; $y++) {
                $tahunData[$y] = 0;
            }
        }
// 4. Program Studi (hanya prodi resmi FST)
$daftarProdiFST = [
    'Matematika',
    'Sistem Informasi',
    'Aktuaria',
];

$prodiData = [];

foreach ($daftarProdiFST as $namaProdi) {
    $prodiData[$namaProdi] = (clone $baseQuery)
        ->whereHas('mahasiswa', function ($query) use ($namaProdi) {
            $query->whereRaw(
                'LOWER(TRIM(prodi)) = ?',
                [strtolower($namaProdi)]
            );
        })
        ->count();
}

        return view('pimpinan.dashboard', compact(
            'totalPrestasi',
            'mahasiswaBerprestasi',
            'kategoriCount',
            'terverifikasi',
            'kategoriData',
            'tingkatData',
            'tahunData',
            'prodiData',
            'q'
        ));
    }

    
public function rekapitulasi(Request $request): View|StreamedResponse
{
    $kategori = $request->input('kategori');
    $tingkat = $request->input('tingkat');
    $tahun = $request->input('tahun');
    $prodi = $request->input('prodi');
    $q = $request->input('q');

    // Daftar program studi resmi FST
    $daftarProdiFST = [
        'Matematika',
        'Sistem Informasi',
        'Aktuaria',
    ];

    // Normalisasi pilihan prodi agar tidak terpengaruh huruf besar/kecil
    $prodiNormalized = $prodi ? strtolower(trim($prodi)) : null;

    // Query utama: hanya mengambil prestasi mahasiswa FST
    $query = Prestasi::query()
        ->with(['mahasiswa'])
        ->join('users', 'users.id', '=', 'prestasi.id_mahasiswa')
        ->select('prestasi.*')
        ->whereRaw(
            'LOWER(TRIM(users.prodi)) IN (?, ?, ?)',
            ['matematika', 'sistem informasi', 'aktuaria']
        )
        ->when($kategori, fn ($b) => $b->where('prestasi.jenis_prestasi', $kategori))
        ->when($tingkat, fn ($b) => $b->where('prestasi.tingkat_prestasi', $tingkat))
        ->when($tahun, fn ($b) => $b->where('prestasi.tahun_perolehan', $tahun))
        ->when($prodiNormalized, function ($b) use ($prodiNormalized) {
            $b->whereRaw('LOWER(TRIM(users.prodi)) = ?', [$prodiNormalized]);
        })
        ->when($q, function ($b) use ($q) {
            $b->where(function ($sub) use ($q) {
                $sub->where('prestasi.nama_prestasi', 'like', "%{$q}%")
                    ->orWhere('users.nama', 'like', "%{$q}%")
                    ->orWhere('users.nim', 'like', "%{$q}%");
            });
        });

    // Export Excel
    if ($request->input('export') === 'excel') {
        $data = (clone $query)
            ->orderByDesc('prestasi.tahun_perolehan')
            ->get();

        return response()->streamDownload(function () use ($data) {
            $out = fopen('php://output', 'w');

            // UTF-8 BOM
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'No', 'Nama Mahasiswa', 'NIM', 'Program Studi',
                'Nama Prestasi', 'Kategori', 'Tingkat', 'Tahun',
                'Perolehan', 'Skor', 'Status'
            ]);

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
        }, 'rekapitulasi_prestasi_'.date('Ymd_His').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    // Data grafik kategori
    $kategoriPersentase = [];
    $totalAll = (clone $query)->count('prestasi.id');

    foreach (Prestasi::JENIS as $kKey => $kLabel) {
        $count = (clone $query)
            ->where('prestasi.jenis_prestasi', $kKey)
            ->count('prestasi.id');

        $percent = $totalAll > 0
            ? round(($count / $totalAll) * 100, 1)
            : 0;

        $kategoriPersentase[$kLabel] = [
            'count' => $count,
            'percent' => $percent,
        ];
    }

    // Grafik prestasi per tahun, mengikuti filter dan hanya data FST
    $rekapPerTahun = (clone $query)
        ->reorder()
        ->select('prestasi.tahun_perolehan')
        ->selectRaw('COUNT(DISTINCT prestasi.id) as total')
        ->groupBy('prestasi.tahun_perolehan')
        ->orderBy('prestasi.tahun_perolehan', 'asc')
        ->pluck('total', 'prestasi.tahun_perolehan')
        ->toArray();

    // Daftar prestasi
    $prestasiList = (clone $query)
        ->orderByDesc('prestasi.created_at')
        ->paginate(15)
        ->withQueryString();

    // Dropdown program studi hanya menampilkan tiga prodi FST
    $prodiList = collect($daftarProdiFST);

    // Dropdown tahun hanya mengambil tahun prestasi dari mahasiswa FST
    $tahunList = Prestasi::query()
        ->join('users', 'users.id', '=', 'prestasi.id_mahasiswa')
        ->whereRaw(
            'LOWER(TRIM(users.prodi)) IN (?, ?, ?)',
            ['matematika', 'sistem informasi', 'aktuaria']
        )
        ->distinct()
        ->orderByDesc('prestasi.tahun_perolehan')
        ->pluck('prestasi.tahun_perolehan');

    return view('pimpinan.rekapitulasi', compact(
        'prestasiList',
        'kategoriPersentase',
        'rekapPerTahun',
        'totalAll',
        'kategori',
        'tingkat',
        'tahun',
        'prodi',
        'q',
        'prodiList',
        'tahunList'
    ));
}
}