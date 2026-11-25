@extends('layout.app')

@section('title', 'Riwayat Data Prestasi - Admin')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color: var(--simpres-olive-dark);">Riwayat Data Prestasi</h1>
            <p class="text-muted mb-0">Daftar seluruh riwayat pengajuan dan verifikasi prestasi mahasiswa FST UIN Imam Bonjol Padang</p>
        </div>
        <div>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-outline-success d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-excel"></i>
                <span>Unduh Riwayat (Excel)</span>
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="content-card mb-4">
        <form method="GET" action="{{ route('admin.riwayat') }}">
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Program Studi</label>
                    <select name="prodi" class="form-select form-select-sm">
                        <option value="">Semua Prodi</option>
                        @foreach ($prodiList as $pr)
                            <option value="{{ $pr }}" @selected(($prodi ?? '') === $pr)>{{ $pr }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Kategori Prestasi</label>
                    <select name="kategori" class="form-select form-select-sm">
                        <option value="">Semua Kategori</option>
                        @foreach (\App\Models\Prestasi::JENIS as $k => $label)
                            <option value="{{ $k }}" @selected($kategori === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Tingkat</label>
                    <select name="tingkat" class="form-select form-select-sm">
                        <option value="">Semua Tingkat</option>
                        @foreach (\App\Models\Prestasi::TINGKAT as $t => $label)
                            <option value="{{ $t }}" @selected($tingkat === $t)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Tahun</label>
                    <select name="tahun" class="form-select form-select-sm">
                        <option value="">Semua Tahun</option>
                        @foreach ($tahunList as $th)
                            <option value="{{ $th }}" @selected((string)$tahun === (string)$th)>{{ $th }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Status Verifikasi</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        @foreach (\App\Models\Prestasi::STATUS as $sKey => $sLabel)
                            <option value="{{ $sKey }}" @selected($status === $sKey)>{{ $sLabel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-olive btn-sm w-100" type="submit">
                        <i class="bi bi-funnel"></i> Terapkan
                    </button>
                </div>
            </div>
            @if ($kategori || $tingkat || $tahun || $status || ($prodi ?? null))
                <div class="mt-3 pt-2 border-top d-flex align-items-center justify-content-between">
                    <div class="small text-muted">
                        <i class="bi bi-funnel-fill text-primary"></i> Filter aktif diterapkan
                    </div>
                    <a href="{{ route('admin.riwayat') }}" class="btn btn-sm btn-link text-decoration-none text-danger p-0">
                        <i class="bi bi-x-circle"></i> Reset Filter
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Riwayat Table Card -->
    <div class="content-card">
        <div class="content-card-header">
            <h2 class="content-card-title">
                <i class="bi bi-journal-text text-success"></i>
                Riwayat Pengajuan Data Prestasi
            </h2>
            <span class="small text-muted">Total: {{ $prestasi->total() }} catatan</span>
        </div>

        @if ($prestasi->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-folder-x fs-1 d-block mb-2 text-secondary"></i>
                <p class="mb-0">Tidak ada data riwayat prestasi yang ditemukan.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-muted small">
                            <th style="width: 50px;">No</th>
                            <th>Mahasiswa</th>
                            <th>Program Studi</th>
                            <th>Nama Prestasi</th>
                            <th>Kategori</th>
                            <th>Tingkat</th>
                            <th>Tahun</th>
                            <th>Status</th>
                            <th>Skor</th>
                            <th class="text-end" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prestasi as $idx => $p)
                            <tr>
                                <td class="text-muted small">{{ $prestasi->firstItem() + $idx }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $p->mahasiswa?->nama ?? '-' }}</div>
                                    <small class="text-muted">NIM: {{ $p->mahasiswa?->nim ?? '-' }}</small>
                                </td>
                                <td>{{ $p->mahasiswa?->prodi ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.verifikasi.show', $p->id) }}" class="fw-semibold text-decoration-none text-dark hover-olive">
                                        {{ $p->nama_prestasi }}
                                    </a>
                                    <div class="small text-muted">{{ $p->perolehanLabel() }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $p->jenisLabel() }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary border">
                                        {{ $p->tingkatLabel() }}
                                    </span>
                                </td>
                                <td>{{ $p->tahun_perolehan }}</td>
                                <td>
                                    @include('partials.status-badge', ['prestasi' => $p])
                                </td>
                                <td>
                                    <strong class="{{ $p->skor !== null ? 'text-success' : 'text-muted' }}">
                                        {{ $p->skorTampil() }}
                                    </strong>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.verifikasi.show', $p->id) }}" class="btn btn-sm btn-outline-secondary" title="Lihat Detail / Verifikasi">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $prestasi->links() }}
            </div>
        @endif
    </div>
@endsection
