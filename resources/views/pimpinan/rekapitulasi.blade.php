@extends('layout.app')

@section('title', 'Rekapitulasi Prestasi - Pimpinan')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color: var(--simpres-olive-dark);">Rekapitulasi Data Prestasi</h1>
            <p class="text-muted mb-0">Laporan capaian prestasi mahasiswa Fakultas Sains dan Teknologi UIN Imam Bonjol Padang</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-outline-success d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-excel"></i>
                <span>Unduh Excel</span>
            </a>
            <button type="button" onclick="window.print()" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                <i class="bi bi-printer"></i>
                <span>Cetak / PDF</span>
            </button>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="content-card mb-4 no-print">
        <form method="GET" action="{{ route('pimpinan.rekapitulasi') }}">
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
                    <a href="{{ route('pimpinan.rekapitulasi') }}" class="btn btn-sm btn-link text-decoration-none text-danger p-0">
                        <i class="bi bi-x-circle"></i> Reset Filter
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Visualisasi Charts Ringkasan (Lap. hal. 129) -->
    <div class="row g-4 mb-4">
        <!-- 1. Distribusi per Kategori -->
        <div class="col-lg-6">
            <div class="content-card h-100">
                <div class="content-card-header">
                    <h2 class="content-card-title">
                        <i class="bi bi-pie-chart text-success"></i>
                        Distribusi per Kategori
                    </h2>
                    <span class="badge bg-light text-dark">{{ $totalAll }} Prestasi</span>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6 text-center">
                        <div style="max-height: 220px; position: relative;">
                            <canvas id="chartKategoriPimpinanRekap"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3 mt-md-0">
                        <ul class="list-group list-group-flush small">
                            @foreach ($kategoriPersentase as $label => $data)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                    <span class="text-truncate me-2">{{ $label }}</span>
                                    <span class="fw-bold">{{ $data['count'] }} <span class="text-muted fw-normal">({{ $data['percent'] }}%)</span></span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Tren Prestasi per Tahun -->
        <div class="col-lg-6">
            <div class="content-card h-100">
                <div class="content-card-header">
                    <h2 class="content-card-title">
                        <i class="bi bi-bar-chart-line text-primary"></i>
                        Tren Prestasi per Tahun
                    </h2>
                </div>
                <div style="height: 220px;">
                    <canvas id="chartTahunPimpinanRekap"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Rekapitulasi Data Prestasi -->
    <div class="content-card">
        <div class="content-card-header">
            <h2 class="content-card-title">
                <i class="bi bi-table text-warning"></i>
                Tabel Rekapitulasi Prestasi FST
            </h2>
            <span class="small text-muted">Total: {{ $prestasiList->total() }} data</span>
        </div>

        @if ($prestasiList->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                <p class="mb-0">Tidak ada data prestasi yang cocok dengan kriteria filter.</p>
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
                            <th>Perolehan</th>
                            <th>Skor</th>
                            <th>Status</th>
                            <th class="text-end" style="width: 80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prestasiList as $idx => $p)
                            <tr>
                                <td class="text-muted small">{{ $prestasiList->firstItem() + $idx }}</td>
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
                                    <span class="fw-semibold">{{ \App\Models\Prestasi::PEROLEHAN[$p->perolehan] ?? $p->perolehan }}</span>
                                </td>
                                <td>
                                    @if ($p->status_verifikasi === 'terverifikasi' && $p->skor !== null)
                                        <span class="badge bg-success-subtle text-success fw-bold">{{ $p->skorTampil() }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @include('partials.rekap-status-badge', ['status' => $p->status_verifikasi])
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('pimpinan.prestasi.show', $p->id) }}" class="btn btn-sm btn-outline-secondary" title="Lihat Detail Prestasi">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $prestasiList->links() }}
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            @media print {
                .sidebar, .topbar, .no-print, .btn, nav {
                    display: none !important;
                }
                .main-wrapper {
                    margin-left: 0 !important;
                    padding: 0 !important;
                }
                .content-card {
                    box-shadow: none !important;
                    border: 1px solid #ccc !important;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // 1. Chart Kategori
                const ctxKategori = document.getElementById('chartKategoriPimpinanRekap');
                if (ctxKategori) {
                    const kategoriLabels = {!! json_encode(array_keys($kategoriPersentase)) !!};
                    const kategoriValues = {!! json_encode(array_column($kategoriPersentase, 'count')) !!};

                    new Chart(ctxKategori, {
                        type: 'doughnut',
                        data: {
                            labels: kategoriLabels,
                            datasets: [{
                                data: kategoriValues,
                                backgroundColor: [
                                    '#5b6743',
                                    '#7a1c2c',
                                    '#cfa83c',
                                    '#2a6f97',
                                    '#6b705c',
                                    '#8d99ae'
                                ],
                                borderWidth: 2,
                                borderColor: '#ffffff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            cutout: '65%'
                        }
                    });
                }

                // 2. Chart Tren Tahun
                const ctxTahun = document.getElementById('chartTahunPimpinanRekap');
                if (ctxTahun) {
                    const tahunLabels = {!! json_encode(array_keys($rekapPerTahun)) !!};
                    const tahunValues = {!! json_encode(array_values($rekapPerTahun)) !!};

                    new Chart(ctxTahun, {
                        type: 'bar',
                        data: {
                            labels: tahunLabels,
                            datasets: [{
                                label: 'Jumlah Prestasi',
                                data: tahunValues,
                                backgroundColor: '#5b6743',
                                borderRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { precision: 0 }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
