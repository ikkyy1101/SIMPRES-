@extends('layout.app')

@section('title', 'Riwayat Prestasi')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
        <div>
            <h4 class="fw-bold mb-1" style="color: #1e293b;">Riwayat Prestasi</h4>
            <p class="text-muted mb-0 small">Berikut adalah daftar prestasi yang telah Anda ajukan ke dalam sistem</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('mahasiswa.prestasi.index', array_merge(request()->query(), ['export' => 'excel'])) }}" class="btn btn-outline-success btn-sm rounded-3">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
            <button type="button" onclick="window.print()" class="btn btn-outline-secondary btn-sm rounded-3">
                <i class="bi bi-printer me-1"></i> Cetak / PDF
            </button>
            <a href="{{ route('mahasiswa.prestasi.create') }}" class="btn btn-olive btn-sm shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Prestasi
            </a>
        </div>
    </div>

    <!-- 4 Summary Stat Chips (Lap. hal. 110-111) -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <a href="{{ route('mahasiswa.prestasi.index') }}" class="text-decoration-none">
                <div class="card border-0 rounded-4 p-3 shadow-sm @if(!request('status')) border-start border-4 border-success @endif bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted fw-semibold">Total Prestasi</small>
                            <h4 class="fw-bold mb-0 text-dark">{{ $totalPrestasi }}</h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-light text-secondary" style="width: 42px; height: 42px;">
                            <i class="bi bi-trophy fs-5"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('mahasiswa.prestasi.index', ['status' => 'terverifikasi']) }}" class="text-decoration-none">
                <div class="card border-0 rounded-4 p-3 shadow-sm @if(request('status') === 'terverifikasi') border-start border-4 border-success @endif bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-success fw-semibold">Terverifikasi</small>
                            <h4 class="fw-bold mb-0 text-success">{{ $terverifikasi }}</h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-success-subtle text-success" style="width: 42px; height: 42px;">
                            <i class="bi bi-check-circle fs-5"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('mahasiswa.prestasi.index', ['status' => 'menunggu']) }}" class="text-decoration-none">
                <div class="card border-0 rounded-4 p-3 shadow-sm @if(request('status') === 'menunggu') border-start border-4 border-warning @endif bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-warning fw-semibold">Dalam Proses</small>
                            <h4 class="fw-bold mb-0 text-warning">{{ $dalamProses }}</h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning-subtle text-warning" style="width: 42px; height: 42px;">
                            <i class="bi bi-hourglass-split fs-5"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('mahasiswa.prestasi.index', ['status' => 'perlu_perbaikan']) }}" class="text-decoration-none">
                <div class="card border-0 rounded-4 p-3 shadow-sm @if(request('status') === 'perlu_perbaikan') border-start border-4 border-danger @endif bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-danger fw-semibold">Perlu Perbaikan</small>
                            <h4 class="fw-bold mb-0 text-danger">{{ $perluPerbaikan }}</h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-danger-subtle text-danger" style="width: 42px; height: 42px;">
                            <i class="bi bi-pencil-square fs-5"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Filter & Pencarian Bar -->
    <div class="content-card mb-4">
        <form method="GET" action="{{ route('mahasiswa.prestasi.index') }}" class="row g-2 align-items-center">
            <div class="col-md-9">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status Verifikasi</option>
                    @foreach (\App\Models\Prestasi::STATUS as $k => $label)
                        <option value="{{ $k }}" @selected($status === $k)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-olive w-100">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                @if($status)
                    <a href="{{ route('mahasiswa.prestasi.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Riwayat Prestasi (Lap. hal. 110-111) -->
    <div class="content-card">
        <div class="content-card-header">
            <h5 class="content-card-title">
                <i class="bi bi-trophy" style="color: var(--simpres-olive);"></i> Daftar Riwayat Prestasi
            </h5>
            <small class="text-muted">Total: {{ $prestasi->total() }} data</small>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="font-size: 13px;">No</th>
                        <th style="font-size: 13px;">Nama Prestasi</th>
                        <th style="font-size: 13px;">Kategori</th>
                        <th style="font-size: 13px;">Tingkat</th>
                        <th style="font-size: 13px;">Tahun</th>
                        <th style="font-size: 13px;">Penyelenggara</th>
                        <th style="font-size: 13px;">Status</th>
                        <th style="font-size: 13px;">Skor</th>
                        <th class="text-center" style="font-size: 13px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($prestasi as $index => $item)
                        <tr>
                            <td class="text-muted small">{{ $prestasi->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold" style="color: #1e293b;">{{ $item->nama_prestasi }}</div>
                                <small class="text-muted">
                                    <i class="bi bi-award"></i> {{ $item->perolehanLabel() }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $item->jenisLabel() }}
                                </span>
                            </td>
                            <td>{{ $item->tingkatLabel() }}</td>
                            <td>{{ $item->tahun_perolehan }}</td>
                            <td><span class="small text-muted">{{ $item->penyelenggara }}</span></td>
                            <td>
                                @include('partials.status-badge', ['prestasi' => $item])
                            </td>
                            <td>
                                <strong class="{{ $item->skor ? 'text-success' : 'text-muted' }}">
                                    {{ $item->skorTampil() }}
                                </strong>
                            </td>
                            <td class="text-center text-nowrap">
                                <a href="{{ route('mahasiswa.prestasi.show', $item) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('update', $item)
                                    <a href="{{ route('mahasiswa.prestasi.edit', $item) }}" class="btn btn-sm btn-outline-danger rounded-circle ms-1" title="Perbaiki Data">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                Riwayat prestasi masih kosong.
                                <div class="mt-2">
                                    <a href="{{ route('mahasiswa.prestasi.create') }}" class="btn btn-sm btn-olive">
                                        Tambah Prestasi Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($prestasi->hasPages())
            <div class="mt-4 d-flex justify-content-between align-items-center">
                <small class="text-muted">Menampilkan {{ $prestasi->firstItem() }} s/d {{ $prestasi->lastItem() }} dari {{ $prestasi->total() }} data</small>
                <div>{{ $prestasi->links() }}</div>
            </div>
        @endif
    </div>
</div>
@endsection
