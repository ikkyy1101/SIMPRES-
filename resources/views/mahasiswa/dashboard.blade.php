@extends('layout.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="container-fluid p-0">
    <!-- Greeting Section (Lap. hal. 103-104) -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: #1e293b;">
                Halo, {{ $user->nama ?? $user->username }} <span class="wave">👋</span>
            </h4>
            <p class="text-muted mb-0 small">Semangat Terus Dalam Meraih Prestasi untuk FST UIN Imam Bonjol Padang!</p>
        </div>
        <div>
            <a href="{{ route('mahasiswa.prestasi.create') }}" class="btn btn-olive shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Prestasi Baru
            </a>
        </div>
    </div>

    <!-- 4 Summary Stat Cards (Lap. hal. 103-104) -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon olive">
                    <i class="bi bi-trophy-fill"></i>
                </div>
                <div>
                    <div class="stat-number">{{ $totalPrestasi }}</div>
                    <div class="stat-label">Total Prestasi</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <div>
                    <div class="stat-number text-success">{{ $terverifikasi }}</div>
                    <div class="stat-label">Terverifikasi</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <div class="stat-number text-warning">{{ $dalamProses }}</div>
                    <div class="stat-label">Dalam Proses</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon danger">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <div class="stat-number text-danger">{{ $perluPerbaikan }}</div>
                    <div class="stat-label">Perlu Perbaikan</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Riwayat Prestasi Saya Table (Lap. hal. 103-104) -->
        <div class="col-lg-8">
            <div class="content-card h-100">
                <div class="content-card-header">
                    <h5 class="content-card-title">
                        <i class="bi bi-journal-check" style="color: var(--simpres-olive);"></i> Riwayat Prestasi Saya
                    </h5>
                    <a href="{{ route('mahasiswa.prestasi.index') }}" class="btn btn-sm btn-outline-secondary rounded-3">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
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
                            @forelse ($riwayatPrestasi as $item)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-truncate" style="max-width: 220px;" title="{{ $item->nama_prestasi }}">
                                            {{ $item->nama_prestasi }}
                                        </div>
                                        <small class="text-muted">{{ $item->penyelenggara }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ \App\Models\Prestasi::JENIS[$item->jenis_prestasi] ?? $item->jenis_prestasi }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="small">{{ \App\Models\Prestasi::TINGKAT[$item->tingkat_prestasi] ?? $item->tingkat_prestasi }}</span>
                                    </td>
                                    <td>{{ $item->tahun_perolehan }}</td>
                                    <td>
                                        @include('partials.status-badge', ['prestasi' => $item])
                                    </td>
                                    <td>
                                        <strong class="{{ $item->skor ? 'text-success' : 'text-muted' }}">
                                            {{ $item->skor !== null ? number_format($item->skor, 0) : '-' }}
                                        </strong>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('mahasiswa.prestasi.show', $item) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($item->status_verifikasi === 'perlu_perbaikan')
                                            <a href="{{ route('mahasiswa.prestasi.edit', $item) }}" class="btn btn-sm btn-outline-warning rounded-circle ms-1" title="Perbaiki Data">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                        Belum ada riwayat prestasi yang diajukan.
                                        <div class="mt-2">
                                            <a href="{{ route('mahasiswa.prestasi.create') }}" class="btn btn-sm btn-olive">
                                                Tambah Prestasi Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Box Pengumuman Terbaru (Lap. hal. 103-104) -->
        <div class="col-lg-4">
            <div class="content-card h-100">
                <div class="content-card-header">
                    <h5 class="content-card-title">
                        <i class="bi bi-megaphone-fill text-warning"></i> Pengumuman Terbaru
                    </h5>
                    <a href="{{ route('mahasiswa.notifikasi.index') }}" class="small text-decoration-none">Semua</a>
                </div>

                @if($pengumuman->isNotEmpty())
                    <div class="d-flex flex-column gap-3">
                        @foreach ($pengumuman as $notif)
                            <div class="p-3 rounded-3 bg-light border-start border-4 @if(str_contains(strtolower($notif->judul), 'terverifikasi')) border-success @elseif(str_contains(strtolower($notif->judul), 'perbaikan')) border-danger @else border-warning @endif">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="small" style="color: #1e293b;">{{ $notif->judul }}</strong>
                                    <small class="text-muted" style="font-size: 11px;">{{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0 text-muted" style="font-size: 12px; line-height: 1.4;">
                                    {{ Str::limit($notif->pesan, 100) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-bell-slash fs-3 d-block mb-1 text-secondary"></i>
                        <small>Belum ada pengumuman atau notifikasi baru.</small>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Diagram Batang Kategori & Quote Motivasi (Lap. hal. 104) -->
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="content-card h-100">
                <div class="content-card-header">
                    <h5 class="content-card-title">
                        <i class="bi bi-bar-chart-fill" style="color: var(--simpres-olive);"></i> Jumlah Prestasi Berdasarkan Kategori
                    </h5>
                </div>
                <div style="height: 250px;">
                    <canvas id="chartKategoriMahasiswa"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 rounded-4 shadow-sm p-4 h-100 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #5b6743 0%, #3e462c 100%);">
                <div class="position-absolute end-0 bottom-0 opacity-25 p-3">
                    <i class="bi bi-award-fill" style="font-size: 120px;"></i>
                </div>
                <div class="position-relative z-1 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="badge bg-warning text-dark mb-3 px-3 py-2 fw-semibold rounded-pill">
                            <i class="bi bi-stars"></i> Motivasi Mahasiswa FST
                        </div>
                        <h4 class="fw-bold mb-3" style="line-height: 1.4;">
                            "Setiap prestasi adalah langkah kecil menuju masa depan yang besar."
                        </h4>
                        <p class="text-white-50 small">
                            Terus asah potensi akademik maupun non-akademik Anda. Fakultas Sains dan Teknologi siap mendukung setiap jejak prestasi Anda!
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-3 pt-3 border-top border-white-50">
                        <div class="rounded-circle bg-white text-dark d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                            🎓
                        </div>
                        <div>
                            <div class="fw-bold small">Fakultas Sains dan Teknologi</div>
                            <small class="text-white-50" style="font-size: 11px;">UIN Imam Bonjol Padang</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('chartKategoriMahasiswa').getContext('2d');
        const labels = {!! json_encode(array_keys($prestasiPerKategori)) !!};
        const data = {!! json_encode(array_values($prestasiPerKategori)) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Prestasi',
                    data: data,
                    backgroundColor: [
                        '#5b6743',
                        '#7a1c2c',
                        '#d4a373',
                        '#2a9d8f',
                        '#e76f51',
                        '#6c757d'
                    ],
                    borderRadius: 8,
                    maxBarThickness: 38
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
                        ticks: { stepSize: 1, precision: 0 }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endpush
