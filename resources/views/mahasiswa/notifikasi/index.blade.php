@extends('layout.app')

@section('title', 'Notifikasi & Pengumuman')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h4 class="fw-bold mb-1" style="color: #1e293b;">Pengumuman dan Notifikasi</h4>
            <p class="text-muted mb-0 small">Lihat perkembangan, pemeriksaan berkas, dan catatan admin terkait prestasi Anda</p>
        </div>
        <div>
            <form action="{{ route('mahasiswa.notifikasi.baca-semua') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm rounded-3">
                    <i class="bi bi-check2-all me-1"></i> Tandai Semua Sudah Dibaca
                </button>
            </form>
        </div>
    </div>

    <!-- Daftar Pengumuman dan Notifikasi (Lap. hal. 111-112) -->
    <div class="vstack gap-3" id="notifListContainer">
        @forelse ($notifikasi as $n)
            @php
                $judulLower = strtolower($n->judul);
                $statusPrestasi = $n->prestasi?->status_verifikasi;
                $isTerverifikasi = $statusPrestasi === 'terverifikasi' || str_contains($judulLower, 'terverifikasi') || str_contains($judulLower, 'berhasil diverifikasi');
                $isDitolak = $statusPrestasi === 'ditolak' || str_contains($judulLower, 'ditolak');
                $isPerbaikan = ! $isTerverifikasi && ! $isDitolak && ($statusPrestasi === 'perlu_perbaikan' || str_contains($judulLower, 'diperbaiki') || str_contains($judulLower, 'perbaikan') || str_contains($judulLower, 'revisi'));

                $badgeColor = $isTerverifikasi ? 'success' : ($isPerbaikan ? 'danger' : ($isDitolak ? 'secondary' : 'warning'));
                $badgeText = $isTerverifikasi ? 'Terverifikasi' : ($isPerbaikan ? 'Perlu Perbaikan' : ($isDitolak ? 'Ditolak' : 'Proses'));
                $iconClass = $isTerverifikasi ? 'bi-patch-check-fill' : ($isPerbaikan ? 'bi-exclamation-triangle-fill' : ($isDitolak ? 'bi-x-circle-fill' : 'bi-hourglass-split'));
                $targetUrl = $n->id_prestasi
                    ? ($isPerbaikan ? route('mahasiswa.prestasi.edit', $n->id_prestasi) : route('mahasiswa.prestasi.show', $n->id_prestasi))
                    : null;
            @endphp
            <div class="card border-0 rounded-4 p-4 shadow-sm notif-item position-relative @unless ($n->sudahDibaca()) border-start border-4 border-{{ $badgeColor }} @endunless"
                 style="background-color: {{ $n->sudahDibaca() ? '#ffffff' : '#fcfcfc' }}; {{ $targetUrl ? 'cursor: pointer;' : '' }}">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-{{ $badgeColor }} px-3 py-1 rounded-pill fw-semibold" style="font-size: 11px;">
                            <i class="bi {{ $iconClass }} me-1"></i> {{ $badgeText }}
                        </span>
                        @if ($targetUrl)
                            <a href="{{ $targetUrl }}" class="fw-bold mb-0 text-dark text-decoration-none stretched-link notif-title">{{ $n->judul }}</a>
                        @else
                            <h6 class="fw-bold mb-0 text-dark notif-title">{{ $n->judul }}</h6>
                        @endif
                    </div>
                    <small class="text-muted" style="font-size: 11px;">
                        <i class="bi bi-clock me-1"></i> {{ $n->created_at->format('d M Y, H:i') }}
                    </small>
                </div>

                <p class="text-secondary small mb-3 notif-desc" style="line-height: 1.5;">
                    {{ $n->pesan }}
                </p>

                <div class="d-flex align-items-center justify-content-end gap-2 border-top pt-2 position-relative" style="z-index: 2;">
                    @if ($n->id_prestasi)
                        @if ($isPerbaikan)
                            <a href="{{ route('mahasiswa.prestasi.edit', $n->id_prestasi) }}" class="btn btn-sm btn-danger rounded-3" style="font-size: 12px; background-color: var(--simpres-maroon); border-color: var(--simpres-maroon);">
                                <i class="bi bi-pencil-square me-1"></i> Buka Halaman Perbaikan
                            </a>
                        @endif
                        <a href="{{ route('mahasiswa.prestasi.show', $n->id_prestasi) }}" class="btn btn-sm btn-outline-primary rounded-3" style="font-size: 12px;">
                            <i class="bi bi-eye me-1"></i> Lihat Detail Prestasi
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="content-card text-center text-muted py-5">
                <i class="bi bi-bell-slash fs-1 d-block mb-2 text-secondary"></i>
                Belum ada notifikasi atau pengumuman masuk.
            </div>
        @endforelse
    </div>

    @if($notifikasi->hasPages())
        <div class="mt-4">{{ $notifikasi->links() }}</div>
    @endif
</div>
@endsection
