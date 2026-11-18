@extends('layout.app')

@section('title', 'Data Prestasi Mahasiswa')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-2">
        <div>
            <h4 class="fw-bold mb-1" style="color: #1e293b;">Data Prestasi Mahasiswa</h4>
            <p class="text-muted mb-0 small">Kelola, periksa berkas, dan lakukan verifikasi data prestasi mahasiswa FST</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.rekapitulasi') }}" class="btn btn-outline-secondary btn-sm rounded-3">
                <i class="bi bi-pie-chart me-1"></i> Rekapitulasi Data
            </a>
            <a href="{{ route('admin.riwayat') }}" class="btn btn-outline-secondary btn-sm rounded-3">
                <i class="bi bi-journal-text me-1"></i> Riwayat Lengkap
            </a>
        </div>
    </div>

    <!-- Filter & Pencarian Form (Lap. hal. 118-119) -->
    <div class="content-card mb-4">
        <form method="GET" action="{{ route('admin.verifikasi.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold" for="q">Pencarian Data</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="q" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control border-start-0" placeholder="Nama mahasiswa, NIM, nama lomba...">
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold" for="prodi">Program Studi</label>
                    <select id="prodi" name="prodi" class="form-select">
                        <option value="">Semua Program Studi</option>
                        @foreach ($prodiList as $pr)
                            <option value="{{ $pr }}" @selected(($filters['prodi'] ?? '') === $pr)>{{ $pr }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold" for="status_verifikasi">Status Verifikasi</label>
                    
                    <select id="status_verifikasi" name="status_verifikasi" class="form-select">
                     <option value="">Semua Status</option>
                     <option value="menunggu_verifikasi" @selected(($filters['status_verifikasi'] ?? '') === 'menunggu_verifikasi')>Menunggu Verifikasi</option>
                     <option value="terverifikasi" @selected(($filters['status_verifikasi'] ?? '') === 'terverifikasi')>Terverifikasi</option>
                     <option value="perlu_perbaikan" @selected(($filters['status_verifikasi'] ?? '') === 'perlu_perbaikan')>Perlu Perbaikan</option>
                    </select>

                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold" for="jenis_prestasi">Kategori</label>
                    <select id="jenis_prestasi" name="jenis_prestasi" class="form-select">
                        <option value="">Semua</option>
                        @foreach (\App\Models\Prestasi::JENIS as $k => $label)
                            <option value="{{ $k }}" @selected(($filters['jenis_prestasi'] ?? '') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold" for="tingkat_prestasi">Tingkat</label>
                    <select id="tingkat_prestasi" name="tingkat_prestasi" class="form-select">
                        <option value="">Semua Tingkat</option>
                        @foreach (\App\Models\Prestasi::TINGKAT as $k => $label)
                            <option value="{{ $k }}" @selected(($filters['tingkat_prestasi'] ?? '') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold" for="perolehan">Perolehan / Hasil</label>
                    <select id="perolehan" name="perolehan" class="form-select">
                        <option value="">Semua Perolehan</option>
                        @foreach (\App\Models\Prestasi::PEROLEHAN as $k => $label)
                            <option value="{{ $k }}" @selected(($filters['perolehan'] ?? '') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold" for="tahun_perolehan">Tahun</label>
                    <input type="number" id="tahun_perolehan" name="tahun_perolehan" min="1900" max="{{ now()->year }}"
                        value="{{ $filters['tahun_perolehan'] ?? '' }}" class="form-control" placeholder="Tahun">
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold" for="skor_min">Skor Min</label>
                    <input type="number" step="0.01" min="0" max="100" id="skor_min" name="skor_min"
                        value="{{ $filters['skor_min'] ?? '' }}" class="form-control" placeholder="0">
                </div>

                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-olive w-100">
                        <i class="bi bi-funnel"></i> Terapkan
                    </button>
                    <a href="{{ route('admin.verifikasi.index') }}" class="btn btn-outline-secondary" title="Reset filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel Data Prestasi (Lap. hal. 118-119) -->
    <div class="content-card">
        <div class="content-card-header">
            <h5 class="content-card-title">
                <i class="bi bi-table" style="color: var(--simpres-olive);"></i> Tabel Data Prestasi Mahasiswa
            </h5>
            <small class="text-muted">Total: {{ $prestasi->total() }} data</small>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="font-size: 13px;">Mahasiswa</th>
                        <th style="font-size: 13px;">Program Studi</th>
                        <th style="font-size: 13px;">Nama Prestasi</th>
                        <th style="font-size: 13px;">Kategori</th>
                        <th style="font-size: 13px;">Tingkat</th>
                        <th style="font-size: 13px;">Tahun</th>
                        <th style="font-size: 13px;">Status</th>
                        <th style="font-size: 13px;">Skor</th>
                        <th class="text-center" style="font-size: 13px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($prestasi as $p)
                        <tr>
                            <td>
                                <div class="fw-bold" style="color: #1e293b;">{{ $p->mahasiswa->nama ?? '-' }}</div>
                                <small class="text-muted">NIM: {{ $p->mahasiswa->nim ?? '-' }}</small>
                            </td>
                            <td>{{ $p->mahasiswa->prodi ?? '-' }}</td>
                            <td>
                                <div class="fw-semibold text-truncate" style="max-width: 220px;" title="{{ $p->nama_prestasi }}">
                                    {{ $p->nama_prestasi }}
                                </div>
                                <small class="text-muted">{{ $p->perolehanLabel() }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $p->jenisLabel() }}</span>
                            </td>
                            <td>{{ $p->tingkatLabel() }}</td>
                            <td>{{ $p->tahun_perolehan }}</td>
                            <td>@include('partials.status-badge', ['prestasi' => $p])</td>
                            <td>
                                <strong class="{{ $p->skor ? 'text-success' : 'text-muted' }}">
                                    {{ $p->skorTampil() }}
                                </strong>
                            </td>
                            <td class="text-center text-nowrap">
                                <!-- Ikon Mata: Lihat Detail (Lap. hal. 118-119) -->
                                <a href="{{ route('admin.verifikasi.show', $p) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <!-- Ikon Pensil: Kelola / Verifikasi (Lap. hal. 118-119) -->
                                <a href="{{ route('admin.verifikasi.show', $p) }}#verifikasi-box" class="btn btn-sm btn-outline-success rounded-circle ms-1" title="Periksa / Verifikasi">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="bi bi-search fs-1 d-block mb-2 text-secondary"></i>
                                Data tidak ditemukan.
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
