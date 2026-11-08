<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrestasiRequest;
use App\Http\Requests\UpdatePrestasiRequest;
use App\Models\Notifikasi;
use App\Models\Prestasi;
use App\Services\BerkasPrestasiService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PrestasiController extends Controller
{
    private const MAKS_DOKUMENTASI = 10;

    public function __construct(private BerkasPrestasiService $berkas) {}

    /** Riwayat prestasi milik mahasiswa yang login (F-07, F-09, F-10, F-11, Lap. hal. 110-111). */
    public function index(Request $request): View|StreamedResponse
    {
        $status = $request->input('status');
        $q = $request->input('q');

        $baseQuery = Prestasi::query()
            ->where('id_mahasiswa', $request->user()->id);

        $totalPrestasi = (clone $baseQuery)->count();
        $terverifikasi = (clone $baseQuery)->where('status_verifikasi', 'terverifikasi')->count();
        $dalamProses = (clone $baseQuery)->where('status_verifikasi', 'menunggu')->count();
        $perluPerbaikan = (clone $baseQuery)->where('status_verifikasi', 'perlu_perbaikan')->count();

        $query = (clone $baseQuery)
            ->when($status, fn ($b) => $b->where('status_verifikasi', $status))
            ->when($q, function ($b) use ($q) {
                $b->where(function ($sub) use ($q) {
                    $sub->where('nama_prestasi', 'like', "%{$q}%")
                        ->orWhere('penyelenggara', 'like', "%{$q}%");
                });
            });

        if ($request->input('export') === 'excel') {
            $data = (clone $query)->orderByDesc('tahun_perolehan')->get();

            return response()->streamDownload(function () use ($data) {
                $out = fopen('php://output', 'w');
                fwrite($out, "\xEF\xBB\xBF");
                fputcsv($out, ['No', 'Nama Prestasi', 'Kategori', 'Tingkat', 'Tahun', 'Penyelenggara', 'Perolehan', 'Status', 'Skor']);

                foreach ($data as $i => $item) {
                    fputcsv($out, [
                        $i + 1,
                        $item->nama_prestasi,
                        Prestasi::JENIS[$item->jenis_prestasi] ?? $item->jenis_prestasi,
                        Prestasi::TINGKAT[$item->tingkat_prestasi] ?? $item->tingkat_prestasi,
                        $item->tahun_perolehan,
                        $item->penyelenggara,
                        Prestasi::PEROLEHAN[$item->perolehan] ?? $item->perolehan,
                        Prestasi::STATUS[$item->status_verifikasi] ?? $item->status_verifikasi,
                        $item->skor ?? '-',
                    ]);
                }
                fclose($out);
            }, 'riwayat_prestasi_saya_'.date('Ymd_His').'.csv', [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]);
        }

        $prestasi = $query
            ->withCount('dokumentasi')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('mahasiswa.prestasi.index', compact(
            'prestasi',
            'status',
            'q',
            'totalPrestasi',
            'terverifikasi',
            'dalamProses',
            'perluPerbaikan'
        ));
    }

    /** Halaman 1: Input Data Prestasi (Lap. hal. 106-107) */
    public function create(Request $request): View
    {
        $draft = $request->session()->get('prestasi_draft', []);

        return view('mahasiswa.prestasi.create', compact('draft'));
    }

    /** Memproses Halaman 1 dan melanjutkan ke Halaman 2 (Upload Sertifikat) */
    public function postStep1(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_prestasi' => ['required', 'string', 'max:200'],
            'jenis_prestasi' => ['required', Rule::in(array_keys(Prestasi::JENIS))],
            'tingkat_prestasi' => ['required', Rule::in(array_keys(Prestasi::TINGKAT))],
            'tahun_perolehan' => ['required', 'integer', 'min:1900', 'max:'.now()->year],
            'tahun_penyelenggaraan' => ['nullable', 'integer', 'min:1900', 'max:'.(now()->year + 1)],
            'penyelenggara' => ['required', 'string', 'max:200'],
            'tempat_pelaksanaan' => ['nullable', 'string', 'max:200'],
            'deskripsi' => ['nullable', 'string', 'max:3000'],
        ]);

        $draft = $request->session()->get('prestasi_draft', []);
        $request->session()->put('prestasi_draft', array_merge($draft, $validated));

        return redirect()
            ->route('mahasiswa.upload.sertifikat')
            ->with('success', 'Data prestasi tersimpan sementara. Lanjutkan dengan mengunggah berkas sertifikat.');
    }

    /** Halaman 2: Upload Sertifikat Mahasiswa (Lap. hal. 107-108) */
    public function uploadSertifikat(Request $request): View
    {
        $draft = $request->session()->get('prestasi_draft', []);
        $prestasiList = Prestasi::query()
            ->where('id_mahasiswa', $request->user()->id)
            ->latest()
            ->get();

        return view('mahasiswa.prestasi.upload-sertifikat', compact('draft', 'prestasiList'));
    }

    /** Memproses Halaman 2 dan melanjutkan ke Halaman 3 (Upload Dokumentasi) */
    public function postUploadSertifikat(Request $request): RedirectResponse
    {
        $rules = [
            'perolehan' => ['required', Rule::in(array_keys(Prestasi::PEROLEHAN))],
            'id_prestasi' => ['nullable', 'exists:prestasi,id'],
        ];

        $draft = $request->session()->get('prestasi_draft', []);
        $hasExistingTemp = ! empty($draft['temp_sertifikat_path']);

        if (! $hasExistingTemp && ! $request->filled('id_prestasi')) {
            $rules['sertifikat'] = ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'];
        } else {
            $rules['sertifikat'] = ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'];
        }

        $validated = $request->validate($rules);

        // Jika mengunggah sertifikat untuk prestasi yang sudah tersimpan sebelumnya di DB
        if (! empty($validated['id_prestasi'])) {
            $prestasi = Prestasi::query()
                ->where('id_mahasiswa', $request->user()->id)
                ->findOrFail($validated['id_prestasi']);

            $prestasi->update(['perolehan' => $validated['perolehan']]);

            if ($request->hasFile('sertifikat')) {
                $baru = [];
                $this->berkas->simpanSertifikat($prestasi, $request->file('sertifikat'), $baru);
            }

            return redirect()
                ->route('mahasiswa.prestasi.show', $prestasi)
                ->with('success', 'Sertifikat dan peringkat prestasi berhasil diperbarui.');
        }

        // Alur Multi-Step: Simpan berkas sementara dan update draft session
        if ($request->hasFile('sertifikat')) {
            $file = $request->file('sertifikat');
            $tempPath = $file->store('temp_sertifikat', 'local');
            $draft['temp_sertifikat_path'] = $tempPath;
            $draft['temp_sertifikat_name'] = $file->getClientOriginalName();
        }

        $draft['perolehan'] = $validated['perolehan'];
        $request->session()->put('prestasi_draft', $draft);

        return redirect()
            ->route('mahasiswa.upload.dokumentasi')
            ->with('success', 'Sertifikat berhasil dipilih. Silakan unggah foto dokumentasi kegiatan jika tersedia.');
    }

    /** Halaman 3: Upload Dokumentasi Kegiatan (Lap. hal. 108-109) */
    public function uploadDokumentasi(Request $request): View
    {
        $draft = $request->session()->get('prestasi_draft', []);
        $prestasiList = Prestasi::query()
            ->where('id_mahasiswa', $request->user()->id)
            ->latest()
            ->get();

        return view('mahasiswa.prestasi.upload-dokumentasi', compact('draft', 'prestasiList'));
    }

    /** Memproses Halaman 3: Finalisasi Penyimpanan ke Database (Lap. hal. 108-109) */
    public function postUploadDokumentasi(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_prestasi' => ['nullable', 'exists:prestasi,id'],
            'dokumentasi' => ['nullable', 'array', 'max:10'],
            'dokumentasi.*' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
            'keterangan_dokumentasi' => ['nullable', 'array'],
            'keterangan_dokumentasi.*' => ['nullable', 'string', 'max:1000'],
        ]);

        // Jika mengunggah dokumentasi untuk prestasi yang sudah ada di DB
        if (! empty($validated['id_prestasi'])) {
            $prestasi = Prestasi::query()
                ->where('id_mahasiswa', $request->user()->id)
                ->findOrFail($validated['id_prestasi']);

            $baru = [];
            $this->berkas->simpanDokumentasi(
                $prestasi,
                array_filter($request->file('dokumentasi') ?? []),
                $validated['keterangan_dokumentasi'] ?? [],
                $baru
            );

            return redirect()
                ->route('mahasiswa.prestasi.show', $prestasi)
                ->with('success', 'Dokumentasi berhasil ditambahkan ke data prestasi.');
        }

        $draft = $request->session()->get('prestasi_draft');

        if (! $draft || empty($draft['nama_prestasi'])) {
            return redirect()
                ->route('mahasiswa.prestasi.create')
                ->withErrors(['nama_prestasi' => 'Silakan lengkapi data prestasi pada Halaman 1 terlebih dahulu.']);
        }

        if (empty($draft['temp_sertifikat_path']) || empty($draft['perolehan'])) {
            return redirect()
                ->route('mahasiswa.upload.sertifikat')
                ->withErrors(['sertifikat' => 'Silakan unggah sertifikat dan pilih peringkat pada Halaman 2 terlebih dahulu.']);
        }

        $user = $request->user();
        $baru = [];

        try {
            $prestasi = DB::transaction(function () use (
                $request,
                $draft,
                $validated,
                $user,
                &$baru
            ) {
                $prestasi = Prestasi::create([
                    'id_mahasiswa' => $user->id,
                    'nama_prestasi' => $draft['nama_prestasi'],
                    'jenis_prestasi' => $draft['jenis_prestasi'],
                    'tingkat_prestasi' => $draft['tingkat_prestasi'],
                    'perolehan' => $draft['perolehan'],
                    'tahun_perolehan' => $draft['tahun_perolehan'],
                    'tahun_penyelenggaraan' => $draft['tahun_penyelenggaraan'] ?? $draft['tahun_perolehan'],
                    'penyelenggara' => $draft['penyelenggara'],
                    'tempat_pelaksanaan' => $draft['tempat_pelaksanaan'] ?? null,
                    'deskripsi' => $draft['deskripsi'] ?? null,
                    'status_verifikasi' => 'menunggu',
                ]);

                // Pindahkan berkas sertifikat dari path sementara ke direktori permanen
                $this->berkas->simpanSertifikatDariPath(
                    $prestasi,
                    $draft['temp_sertifikat_path'],
                    $draft['temp_sertifikat_name'] ?? 'sertifikat.pdf',
                    $baru
                );

                // Simpan dokumentasi jika dilampirkan
                $this->berkas->simpanDokumentasi(
                    $prestasi,
                    array_filter($request->file('dokumentasi') ?? []),
                    $validated['keterangan_dokumentasi'] ?? [],
                    $baru
                );

                return $prestasi;
            });
        } catch (\Throwable $e) {
            $this->berkas->hapus($baru);
            throw $e;
        }

        // Bersihkan draft session setelah berhasil disimpan permanen
        $request->session()->forget('prestasi_draft');

        return redirect()
            ->route('mahasiswa.prestasi.show', $prestasi)
            ->with('success', 'Prestasi berhasil diajukan dan sedang menunggu verifikasi Admin.');
    }

    /** Membatalkan pengisian draft dan kembali ke daftar prestasi */
    public function cancelDraft(Request $request): RedirectResponse
    {
        $draft = $request->session()->get('prestasi_draft', []);
        if (! empty($draft['temp_sertifikat_path'])) {
            Storage::disk('local')->delete($draft['temp_sertifikat_path']);
        }
        $request->session()->forget('prestasi_draft');

        return redirect()->route('mahasiswa.prestasi.index');
    }

    public function store(StorePrestasiRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = $request->user();
        $baru = [];

        try {
            $prestasi = DB::transaction(function () use (
                $request,
                $data,
                $user,
                &$baru
            ) {
                $prestasi = Prestasi::create([
                    'id_mahasiswa' => $user->id,
                    'nama_prestasi' => $data['nama_prestasi'],
                    'jenis_prestasi' => $data['jenis_prestasi'],
                    'tingkat_prestasi' => $data['tingkat_prestasi'],
                    'perolehan' => $data['perolehan'],
                    'tahun_perolehan' => $data['tahun_perolehan'],
                    'tahun_penyelenggaraan' => $data['tahun_penyelenggaraan'] ?? $data['tahun_perolehan'],
                    'penyelenggara' => $data['penyelenggara'],
                    'tempat_pelaksanaan' => $data['tempat_pelaksanaan'] ?? null,
                    'deskripsi' => $data['deskripsi'] ?? null,
                    'status_verifikasi' => 'menunggu',
                ]);

                $this->berkas->simpanSertifikat(
                    $prestasi,
                    $request->file('sertifikat'),
                    $baru
                );

                $this->berkas->simpanDokumentasi(
                    $prestasi,
                    array_filter($request->file('dokumentasi') ?? []),
                    $data['keterangan_dokumentasi'] ?? [],
                    $baru
                );

                return $prestasi;
            });
        } catch (\Throwable $e) {
            // Transaksi DB di-rollback otomatis; berkas yang sudah tertulis dihapus.
            $this->berkas->hapus($baru);

            throw $e;
        }

        return redirect()
            ->route('mahasiswa.prestasi.show', $prestasi)
            ->with('success', 'Prestasi berhasil diajukan dan menunggu verifikasi Admin.');
    }

    public function show(Request $request, Prestasi $prestasi): View
    {
        Gate::authorize('view', $prestasi);

        abort_unless(
            (int) $prestasi->id_mahasiswa === (int) $request->user()->id,
            403
        );

        $prestasi->load(['sertifikat', 'dokumentasi', 'verifikator']);

        // Activity 2.5: notifikasi terkait ditandai sudah dibaca saat detail dibuka.
        Notifikasi::query()
            ->where('id_user', $request->user()->id)
            ->where('id_prestasi', $prestasi->id)
            ->whereNull('dibaca_pada')
            ->update(['dibaca_pada' => now()]);

        return view('mahasiswa.prestasi.show', compact('prestasi'));
    }

    /** Form perbaikan - hanya untuk status "Perlu Perbaikan" (F-13). */
    public function edit(Prestasi $prestasi): View
    {
        Gate::authorize('update', $prestasi);

        $prestasi->load(['sertifikat', 'dokumentasi']);

        return view('mahasiswa.prestasi.edit', compact('prestasi'));
    }

    public function update(
        UpdatePrestasiRequest $request,
        Prestasi $prestasi
    ): RedirectResponse {
        Gate::authorize('update', $prestasi);

        $data = $request->validated();
        $baru = [];
        $lama = [];

        try {
            DB::transaction(function () use (
                $request,
                $data,
                $prestasi,
                &$baru,
                &$lama
            ) {
                $locked = Prestasi::query()
                    ->whereKey($prestasi->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    (int) $locked->id_mahasiswa !== (int) $request->user()->id
                    || $locked->status_verifikasi !== 'perlu_perbaikan'
                ) {
                    throw ValidationException::withMessages([
                        'prestasi' => 'Hanya pengajuan milik sendiri yang '
                            .'berstatus Perlu Perbaikan yang dapat diubah.',
                    ]);
                }

                // Diajukan ulang -> kembali ke antrean verifikasi.
                // catatan_revisi lama sengaja dipertahankan agar Admin bisa
                // memeriksa apakah catatannya sudah ditindaklanjuti.
                $locked->update([
                    'nama_prestasi' => $data['nama_prestasi'],
                    'jenis_prestasi' => $data['jenis_prestasi'],
                    'tingkat_prestasi' => $data['tingkat_prestasi'],
                    'perolehan' => $data['perolehan'],
                    'tahun_perolehan' => $data['tahun_perolehan'],
                    'tahun_penyelenggaraan' => $data['tahun_penyelenggaraan'] ?? $data['tahun_perolehan'],
                    'penyelenggara' => $data['penyelenggara'],
                    'tempat_pelaksanaan' => $data['tempat_pelaksanaan'] ?? null,
                    'deskripsi' => $data['deskripsi'] ?? null,
                    'status_verifikasi' => 'menunggu',
                    'skor' => null,
                    'id_admin' => null,
                    'diverifikasi_pada' => null,
                ]);

                // Sertifikat: ganti hanya bila ada unggahan baru.
                if ($request->hasFile('sertifikat')) {
                    $lama = array_merge(
                        $lama,
                        $locked->sertifikat()->pluck('file_sertifikat')->all()
                    );

                    $locked->sertifikat()->delete();

                    $this->berkas->simpanSertifikat(
                        $locked,
                        $request->file('sertifikat'),
                        $baru
                    );
                }

                // Dokumentasi: hapus yang dipilih, lalu tambahkan yang baru.
                $hapusIds = array_map('intval', $data['hapus_dokumentasi'] ?? []);

                if ($hapusIds !== []) {
                    $dihapus = $locked->dokumentasi()
                        ->whereIn('id', $hapusIds)
                        ->get();

                    foreach ($dihapus as $dok) {
                        $lama[] = $dok->file_dokumentasi;
                        $dok->delete();
                    }
                }

                $fileBaru = array_filter($request->file('dokumentasi') ?? []);

                if (
                    $locked->dokumentasi()->count() + count($fileBaru)
                    > self::MAKS_DOKUMENTASI
                ) {
                    throw ValidationException::withMessages([
                        'dokumentasi' => 'Maksimal '.self::MAKS_DOKUMENTASI
                            .' file dokumentasi per prestasi.',
                    ]);
                }

                $this->berkas->simpanDokumentasi(
                    $locked,
                    $fileBaru,
                    $data['keterangan_dokumentasi'] ?? [],
                    $baru
                );
            });
        } catch (\Throwable $e) {
            $this->berkas->hapus($baru);

            throw $e;
        }

        // Berkas lama baru dihapus setelah transaksi sukses.
        $this->berkas->hapus($lama);

        return redirect()
            ->route('mahasiswa.prestasi.show', $prestasi)
            ->with('success', 'Perbaikan berhasil dikirim ulang dan menunggu verifikasi.');
    }
}
