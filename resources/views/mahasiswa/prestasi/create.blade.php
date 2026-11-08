@extends('layout.app')

@section('title', 'Tambah Data Prestasi')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-2 gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: #100e0e; font-size: 24px; letter-spacing: -0.3px;">Tambah Data Prestasi</h4>
            <p class="text-muted mb-0 small">Lengkapi rincian data kegiatan dan prestasi akademik maupun non-akademik mahasiswa.</p>
        </div>
        <div>
            <a href="{{ route('mahasiswa.prestasi.index') }}" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-medium">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
            </a>
        </div>
    </div>

    <hr class="my-3 opacity-25" style="border-top: 2px solid #cbd5e1;">

    @include('mahasiswa.prestasi._stepper', ['currentStep' => 1])

    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 p-md-5">
        <form action="{{ route('mahasiswa.prestasi.post-step1') }}" method="POST">
            @csrf

            <div class="row g-4">
                <!-- Baris 1: Nama Prestasi & Jenis / Kategori -->
                <div class="col-lg-6">
                    <label for="nama_prestasi" class="form-label fw-bold mb-2 text-secondary" style="font-size: 15px;">
                        Nama Prestasi <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="nama_prestasi" name="nama_prestasi" maxlength="200"
                        value="{{ old('nama_prestasi', $draft['nama_prestasi'] ?? '') }}"
                        class="form-control form-control-figma @error('nama_prestasi') is-invalid @enderror"
                        placeholder="Masukkan Nama Prestasi" required>
                    @error('nama_prestasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="jenis_prestasi" class="form-label fw-bold mb-2 text-secondary" style="font-size: 15px;">
                        Jenis / Kategori <span class="text-danger">*</span>
                    </label>
                    <select id="jenis_prestasi" name="jenis_prestasi"
                        class="form-select form-select-figma @error('jenis_prestasi') is-invalid @enderror" required>
                        <option value="" disabled {{ old('jenis_prestasi', $draft['jenis_prestasi'] ?? '') ? '' : 'selected' }}>pilih jenis prestasi</option>
                        @foreach (\App\Models\Prestasi::JENIS as $k => $label)
                            <option value="{{ $k }}" @selected(old('jenis_prestasi', $draft['jenis_prestasi'] ?? '') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('jenis_prestasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Baris 2: Tingkat Prestasi & Tahun Perolehan -->
                <div class="col-lg-6">
                    <label for="tingkat_prestasi" class="form-label fw-bold mb-2 text-secondary" style="font-size: 15px;">
                        Tingkat Prestasi <span class="text-danger">*</span>
                    </label>
                    <select id="tingkat_prestasi" name="tingkat_prestasi"
                        class="form-select form-select-figma @error('tingkat_prestasi') is-invalid @enderror" required>
                        <option value="" disabled {{ old('tingkat_prestasi', $draft['tingkat_prestasi'] ?? '') ? '' : 'selected' }}>pilih tingkat prestasi</option>
                        @foreach (\App\Models\Prestasi::TINGKAT as $k => $label)
                            <option value="{{ $k }}" @selected(old('tingkat_prestasi', $draft['tingkat_prestasi'] ?? '') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('tingkat_prestasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="tahun_perolehan" class="form-label fw-bold mb-2 text-secondary" style="font-size: 15px;">
                        Tahun Perolehan <span class="text-danger">*</span>
                    </label>
                    <input type="number" id="tahun_perolehan" name="tahun_perolehan" min="1900" max="{{ now()->year }}"
                        value="{{ old('tahun_perolehan', $draft['tahun_perolehan'] ?? date('Y')) }}"
                        class="form-control form-control-figma @error('tahun_perolehan') is-invalid @enderror"
                        placeholder="pilih tahun" required>
                    @error('tahun_perolehan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Baris 3: Tahun Penyelenggaraan & Pihak Penyelenggara -->
                <div class="col-lg-6">
                    <label for="tahun_penyelenggaraan" class="form-label fw-bold mb-2 text-secondary" style="font-size: 15px;">
                        Tahun Penyelenggaraan
                    </label>
                    <input type="number" id="tahun_penyelenggaraan" name="tahun_penyelenggaraan" min="1900" max="{{ now()->year + 1 }}"
                        value="{{ old('tahun_penyelenggaraan', $draft['tahun_penyelenggaraan'] ?? date('Y')) }}"
                        class="form-control form-control-figma @error('tahun_penyelenggaraan') is-invalid @enderror"
                        placeholder="masukkan penyelenggaraan">
                    @error('tahun_penyelenggaraan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="penyelenggara" class="form-label fw-bold mb-2 text-secondary" style="font-size: 15px;">
                        Pihak Penyelenggara <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="penyelenggara" name="penyelenggara" maxlength="200"
                        value="{{ old('penyelenggara', $draft['penyelenggara'] ?? '') }}"
                        class="form-control form-control-figma @error('penyelenggara') is-invalid @enderror"
                        placeholder="masukkan penyelenggara kegiatan" required>
                    @error('penyelenggara')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Baris 4: Tempat Pelaksanaan & Deskripsi -->
                <div class="col-lg-6">
                    <label for="tempat_pelaksanaan" class="form-label fw-bold mb-2 text-secondary" style="font-size: 15px;">
                        Tempat Pelaksanaan
                    </label>
                    <input type="text" id="tempat_pelaksanaan" name="tempat_pelaksanaan" maxlength="200"
                        value="{{ old('tempat_pelaksanaan', $draft['tempat_pelaksanaan'] ?? '') }}"
                        class="form-control form-control-figma @error('tempat_pelaksanaan') is-invalid @enderror"
                        placeholder="masukkan tempat pelaksanaan">
                    @error('tempat_pelaksanaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="deskripsi" class="form-label fw-bold mb-2 text-secondary" style="font-size: 15px;">
                        Keterangan atau Informasi Tambahan
                    </label>
                    <input type="text" id="deskripsi" name="deskripsi" maxlength="1000"
                        value="{{ old('deskripsi', $draft['deskripsi'] ?? '') }}"
                        class="form-control form-control-figma @error('deskripsi') is-invalid @enderror"
                        placeholder="masukkan catatan ringkas kegiatan">
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex flex-wrap align-items-center justify-content-center gap-3 pt-5 mt-4">
                <a href="{{ route('mahasiswa.prestasi.index') }}" class="btn btn-figma-cancel px-4">
                    Batal
                </a>
                <button type="submit" class="btn btn-figma-olive px-4 shadow-sm">
                    Simpan & Lanjut <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
