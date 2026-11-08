@extends('layout.app')

@section('title', $prestasi->nama_prestasi)

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h4 class="fw-bold mb-1" style="color: #1e293b;">Detail Prestasi Mahasiswa</h4>
            <p class="text-muted mb-0 small">Informasi lengkap pengajuan prestasi dan status verifikasi</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pimpinan.prestasi.index') }}" class="btn btn-outline-secondary rounded-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Data Prestasi
            </a>
        </div>
    </div>

    @if ($prestasi->status_verifikasi === 'perlu_perbaikan')
        <div class="alert alert-danger border-0 rounded-4 p-3 mb-4 shadow-sm d-flex align-items-center gap-3">
            <i class="bi bi-exclamation-octagon-fill fs-3 text-danger"></i>
            <div>
                <strong>Pengajuan ini Memerlukan Perbaikan!</strong><br>
                <span class="small">Admin telah memeriksa berkas Anda dan meminta perbaikan data atau unggahan ulang dokumen. Silakan klik tombol <strong>Perbaiki Data</strong> di atas.</span>
            </div>
        </div>
    @elseif ($prestasi->status_verifikasi === 'terverifikasi')
        <div class="alert alert-success border-0 rounded-4 p-3 mb-4 shadow-sm d-flex align-items-center gap-3">
            <i class="bi bi-check-circle-fill fs-3 text-success"></i>
            <div>
                <strong>Selamat! Prestasi Telah Terverifikasi</strong><br>
                <span class="small">Prestasi ini telah disetujui oleh pihak Admin Fakultas dengan skor {{ $prestasi->skorTampil() }}.</span>
            </div>
        </div>
    @endif

    @include('partials.detail-prestasi', ['prestasi' => $prestasi])
</div>
@endsection
