<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MahasiswaRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Manajemen data mahasiswa (deliverable November) dan riwayat prestasi
 * per mahasiswa (F-31).
 */
class MahasiswaController extends Controller
{
    public function index(Request $request): View
    {
        $f = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'prodi' => ['nullable', 'string', Rule::in(User::PROGRAM_STUDI)],
        ]);

        $mahasiswa = User::query()
            ->where('role', 'mahasiswa')
            ->when($f['prodi'] ?? null, fn ($q, $p) => $q->where('prodi', $p))
            ->when($f['q'] ?? null, function ($q, $kata) {
                $like = '%'.addcslashes($kata, '\\%_').'%';

                $q->where(function ($b) use ($like) {
                    $b->where('nama', 'like', $like)
                        ->orWhere('nim', 'like', $like);
                });
            })
            ->withCount([
                'prestasi',
                'prestasi as terverifikasi_count' => fn ($q) => $q->where('status_verifikasi', 'terverifikasi'),
            ])
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        $prodiList = User::PROGRAM_STUDI;

        return view('admin.mahasiswa.index', compact('mahasiswa', 'prodiList', 'f'));
    }

    public function create(): View
    {
        return view('admin.mahasiswa.create', ['prodiList' => User::PROGRAM_STUDI]);
    }

    public function store(MahasiswaRequest $request): RedirectResponse
    {
        $mahasiswa = User::create($request->validated() + ['role' => 'mahasiswa']);

        return redirect()
            ->route('admin.mahasiswa.show', $mahasiswa)
            ->with('success', 'Akun mahasiswa berhasil dibuat.');
    }

    public function show(User $mahasiswa): View
    {
        abort_unless($mahasiswa->isMahasiswa(), 404);

        $prestasi = $mahasiswa->prestasi()
            ->latest()
            ->paginate(10);

        return view('admin.mahasiswa.show', compact('mahasiswa', 'prestasi'));
    }

    public function edit(User $mahasiswa): View
    {
        abort_unless($mahasiswa->isMahasiswa(), 404);

        return view('admin.mahasiswa.edit', [
            'mahasiswa' => $mahasiswa,
            'prodiList' => User::PROGRAM_STUDI,
        ]);
    }

    public function update(MahasiswaRequest $request, User $mahasiswa): RedirectResponse
    {
        abort_unless($mahasiswa->isMahasiswa(), 404);

        $data = $request->validated();

        // Password kosong = tidak diubah.
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $mahasiswa->update($data);

        return redirect()
            ->route('admin.mahasiswa.show', $mahasiswa)
            ->with('success', 'Data mahasiswa diperbarui.');
    }
}
