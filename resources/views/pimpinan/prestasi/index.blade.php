@extends('layout.app')

@section('title', 'Data Prestasi Mahasiswa - Pimpinan')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color: var(--simpres-olive-dark);">Data Prestasi Mahasiswa</h1>
            <p class="text-muted mb-0">Daftar seluruh data capaian prestasi mahasiswa FST UIN Imam Bonjol Padang (Akses Pemantauan)</p>
        </div>
        <div>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-outline-success d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-excel"></i>
                <span>Unduh Data (Excel)</span>
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="content-card mb-4">
        <form method="GET" action="{{ route('pimpinan.prestasi.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
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
                    <label class="form-label small fw-semibold text-muted mb-1">Tahun Perolehan</label>
                    <select name="tahun" class="form-select form-select-sm">
                        <option value="">Semua Tahun</option>
                        @foreach ($tahunList as $th)
                            <option value="{{ $th }}" @selected((string)$tahun === (string)$th)>{{ $th }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Program Studi</label>
                    <select name="prodi" class="form-select form-select-sm">
                        <option value="">Semua Prodi</option>
                        @foreach ($prodiList as $pr)
                            <option value="{{ $pr }}" @selected($prodi === $pr)>{{ $pr }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-olive btn-sm w-100" type="submit">
                        <i class="bi bi-funnel"></i> Terapkan
                    </button>
                </div>
            </div>
            @if ($kategori || $tingkat || $tahun || $prodi)
                <div class="mt-3 pt-2 border-top d-flex align-items-center justify-content-between">
                    <div class="small text-muted">
                        <i class="bi bi-funnel-fill text-primary"></i> Filter aktif diterapkan
                    </div>
                    <a href="{{ route('pimpinan.prestasi.index') }}" class="btn btn-sm btn-link text-decoration-none text-danger p-0">
                        <i class="bi bi-x-circle"></i> Reset Filter
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="content-card">
        <div class="content-card-header">
            <h2 class="content-card-title">
                <i class="bi bi-file-earmark-bar-graph text-success"></i>
                Daftar Prestasi Mahasiswa
            </h2>
            <span class="small text-muted">Menampilkan {{ $prestasi->total() }} catatan</span>
        </div>

        @if ($prestasi->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                <p class="mb-0">Tidak ada data prestasi yang ditemukan.</p>
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
                            <th>Status Verifikasi</th>
                            <th>Skor</th>
                            <th class="text-end" style="width: 90px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prestasi as $idx => $p)
                            <tr>
                                <td class="text-muted small">{{ $prestasi->firstItem() + $idx }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $p->mahasiswa?->nama ?? '-' }}</div>
                                    <small class="text-muted">{{ $p->mahasiswa?->nim ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="small">{{ $p->mahasiswa?->prodi ?? '-' }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('pimpinan.prestasi.show', $p->id) }}" class="fw-semibold text-decoration-none text-dark hover-olive">
                                        {{ $p->nama_prestasi }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ \App\Models\Prestasi::JENIS[$p->jenis_prestasi] ?? $p->jenis_prestasi }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary border">
                                        {{ \App\Models\Prestasi::TINGKAT[$p->tingkat_prestasi] ?? $p->tingkat_prestasi }}
                                    </span>
                                </td>
                                <td>{{ $p->tahun_perolehan }}</td>
                                <td>
                                    @include('partials.status-badge', ['status' => $p->status_verifikasi])
                                </td>
                                <td>
                                    @if ($p->skor)
                                        <span class="badge bg-success-subtle text-success fw-bold">{{ $p->skor }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('pimpinan.prestasi.show', $p->id) }}" class="btn btn-sm btn-outline-secondary" title="Lihat Detail Prestasi">
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
