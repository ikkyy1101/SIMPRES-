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
                $isTerverifikasi = str_contains($judulLower, 'terverifikasi') || str_contains($judulLower, 'berhasil diverifikasi');
                $isPerbaikan = str_contains($judulLower, 'perbaikan') || str_contains($judulLower, 'ditolak') || str_contains($judulLower, 'revisi');

                $badgeColor = $isTerverifikasi ? 'success' : ($isPerbaikan ? 'danger' : 'warning');
                $badgeText = $isTerverifikasi ? 'Terverifikasi' : ($isPerbaikan ? 'Perbaikan' : 'Proses');
                $iconClass = $isTerverifikasi ? 'bi-patch-check-fill' : ($isPerbaikan ? 'bi-exclamation-triangle-fill' : 'bi-hourglass-split');
            @endphp
            <div class="card border-0 rounded-4 p-4 shadow-sm notif-item @unless ($n->sudahDibaca()) border-start border-4 border-{{ $badgeColor }} @endunless" style="background-color: {{ $n->sudahDibaca() ? '#ffffff' : '#fcfcfc' }};">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-{{ $badgeColor }} px-3 py-1 rounded-pill fw-semibold" style="font-size: 11px;">
                            <i class="bi {{ $iconClass }} me-1"></i> {{ $badgeText }}
                        </span>
                        <h6 class="fw-bold mb-0 text-dark notif-title">{{ $n->judul }}</h6>
                    </div>
                    <small class="text-muted" style="font-size: 11px;">
                        <i class="bi bi-clock me-1"></i> {{ $n->created_at->format('d M Y, H:i') }}
                    </small>
                </div>

                <p class="text-secondary small mb-3 notif-desc" style="line-height: 1.5;">
                    {{ $n->pesan }}
                </p>

                <div class="d-flex align-items-center justify-content-end gap-2 border-top pt-2">
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

@push('scripts')
<script>
    function filterNotifikasi() {
        const val = document.getElementById('filterNotifInput').value.toLowerCase();
        const items = document.querySelectorAll('.notif-item');
        items.forEach(item => {
            const text = item.innerText.toLowerCase();
            item.style.display = text.includes(val) ? '' : 'none';
        });
    }
</script>
@endpush
