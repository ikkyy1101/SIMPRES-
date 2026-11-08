@extends('layout.app')

@section('title', 'Halaman Perbaikan Data Prestasi')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-2 gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: #100e0e; font-size: 24px; letter-spacing: -0.3px;">Halaman Perbaikan</h4>
            <p class="text-muted mb-0 small">Perbaiki rincian data kegiatan atau unggah ulang berkas sesuai catatan verifikasi dari Admin.</p>
        </div>
        <div>
            <a href="{{ route('mahasiswa.prestasi.show', $prestasi) }}" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-medium">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail
            </a>
        </div>
    </div>

    <hr class="my-3 opacity-25" style="border-top: 2px solid #cbd5e1;">

    @if ($prestasi->catatan_revisi)
        <div class="alert border-0 rounded-4 p-4 mb-4 shadow-sm"
             style="background-color: #fff8e6; border-left: 5px solid #d4a373 !important;">
            <div class="d-flex align-items-center gap-2 mb-2 text-warning-emphasis">
                <i class="bi bi-exclamation-triangle-fill fs-5 text-warning"></i>
                <h6 class="fw-bold mb-0 text-dark">Catatan Perbaikan dari Verifikator:</h6>
            </div>
            <p class="mb-0 text-dark ps-4 fw-medium">{{ $prestasi->catatan_revisi }}</p>
        </div>
    @endif

    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 p-md-5">
        <form action="{{ route('mahasiswa.prestasi.update', $prestasi) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Bagian 1: Rincian Data Prestasi (2 Kolom) -->
            <div class="row g-4">
                <div class="col-lg-6">
                    <label for="nama_prestasi" class="form-label fw-bold mb-2 text-secondary" style="font-size: 15px;">
                        Nama Prestasi <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="nama_prestasi" name="nama_prestasi" maxlength="200"
                        value="{{ old('nama_prestasi', $prestasi->nama_prestasi) }}"
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
                        @foreach (\App\Models\Prestasi::JENIS as $k => $label)
                            <option value="{{ $k }}" @selected(old('jenis_prestasi', $prestasi->jenis_prestasi) === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('jenis_prestasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="tingkat_prestasi" class="form-label fw-bold mb-2 text-secondary" style="font-size: 15px;">
                        Tingkat Prestasi <span class="text-danger">*</span>
                    </label>
                    <select id="tingkat_prestasi" name="tingkat_prestasi"
                        class="form-select form-select-figma @error('tingkat_prestasi') is-invalid @enderror" required>
                        @foreach (\App\Models\Prestasi::TINGKAT as $k => $label)
                            <option value="{{ $k }}" @selected(old('tingkat_prestasi', $prestasi->tingkat_prestasi) === $k)>{{ $label }}</option>
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
                        value="{{ old('tahun_perolehan', $prestasi->tahun_perolehan) }}"
                        class="form-control form-control-figma @error('tahun_perolehan') is-invalid @enderror"
                        placeholder="pilih tahun" required>
                    @error('tahun_perolehan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="tahun_penyelenggaraan" class="form-label fw-bold mb-2 text-secondary" style="font-size: 15px;">
                        Tahun Penyelenggaraan
                    </label>
                    <input type="number" id="tahun_penyelenggaraan" name="tahun_penyelenggaraan" min="1900" max="{{ now()->year + 1 }}"
                        value="{{ old('tahun_penyelenggaraan', $prestasi->tahun_penyelenggaraan) }}"
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
                        value="{{ old('penyelenggara', $prestasi->penyelenggara) }}"
                        class="form-control form-control-figma @error('penyelenggara') is-invalid @enderror"
                        placeholder="masukkan pihak penyelenggara" required>
                    @error('penyelenggara')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="tempat_pelaksanaan" class="form-label fw-bold mb-2 text-secondary" style="font-size: 15px;">
                        Tempat Pelaksanaan
                    </label>
                    <input type="text" id="tempat_pelaksanaan" name="tempat_pelaksanaan" maxlength="200"
                        value="{{ old('tempat_pelaksanaan', $prestasi->tempat_pelaksanaan) }}"
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
                        value="{{ old('deskripsi', $prestasi->deskripsi) }}"
                        class="form-control form-control-figma @error('deskripsi') is-invalid @enderror"
                        placeholder="masukkan catatan ringkas kegiatan">
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="my-4 opacity-25" style="border-top: 1px solid #cbd5e1;">

            <!-- Bagian 2: Upload Sertifikat, Upload Dokumentasi & Peringkat -->
            <div class="row g-4 align-items-stretch">
                <!-- Upload Sertifikat Box -->
                <div class="col-lg-4">
                    <label class="form-label fw-bold mb-2 text-secondary" style="font-size: 15px;">
                        Upload Sertifikat
                    </label>

                    @if ($prestasi->sertifikat)
                        <div class="p-2 mb-2 rounded-3 bg-light border d-flex align-items-center justify-content-between small">
                            <span class="text-truncate me-2 fw-semibold text-dark">
                                <i class="bi bi-file-earmark-check text-success me-1"></i> Sertifikat tersimpan
                            </span>
                            <a href="{{ route('berkas.sertifikat', $prestasi) }}" target="_blank" class="text-decoration-none small fw-bold" style="color: #535020;">Lihat</a>
                        </div>
                    @endif

                    <div class="dropzone-figma p-4 text-center d-flex flex-column align-items-center justify-content-center h-100"
                         style="min-height: 220px;">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                             style="width: 56px; height: 56px; background: rgba(83, 80, 32, 0.1); color: #535020;">
                            <i class="bi bi-cloud-arrow-up-fill fs-3"></i>
                        </div>
                        <div class="fw-bold text-dark small mb-1">Pilih file atau drag & drop</div>
                        <div class="text-muted" style="font-size: 11px;">(PDF, DOC, DOCX, JPG, PNG)</div>
                        <div class="mt-3">
                            <input type="file" id="sertifikat" name="sertifikat" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                   class="form-control form-control-sm @error('sertifikat') is-invalid @enderror">
                        </div>
                        @error('sertifikat')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Upload Dokumentasi Box -->
                <div class="col-lg-4">
                    <label class="form-label fw-bold mb-2 text-secondary" style="font-size: 15px;">
                        Upload Dokumentasi
                    </label>

                    @if ($prestasi->dokumentasi->isNotEmpty())
                        <div class="p-2 mb-2 rounded-3 bg-light border small text-muted">
                            <i class="bi bi-images me-1 text-primary"></i> {{ $prestasi->dokumentasi->count() }} dokumentasi terlampir sebelumnya.
                        </div>
                    @endif

                    <div class="dropzone-figma p-4 text-center d-flex flex-column align-items-center justify-content-center h-100"
                         style="min-height: 220px;">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                             style="width: 56px; height: 56px; background: rgba(83, 80, 32, 0.1); color: #535020;">
                            <i class="bi bi-cloud-arrow-up-fill fs-3"></i>
                        </div>
                        <div class="fw-bold text-dark small mb-1">Pilih file atau drag & drop</div>
                        <div class="text-muted" style="font-size: 11px;">(PDF, DOC, DOCX, JPG, PNG)</div>
                        <div class="mt-3">
                            <input type="file" class="form-control form-control-sm" name="dokumentasi[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>

                <!-- Peringkat Selector -->
                <div class="col-lg-4">
                    <label class="form-label fw-bold mb-2 text-secondary" style="font-size: 15px;">
                        Peringkat : <span class="text-danger">*</span>
                    </label>

                    <div class="vstack gap-2" style="background-color: #f8fafc; padding: 14px; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <label class="p-2 px-3 rounded-3 border bg-white d-flex align-items-center justify-content-between cursor-pointer" for="perolehan_juara_1">
                            <div class="d-flex align-items-center gap-2">
                                <input class="form-check-input mt-0" type="radio" name="perolehan" id="perolehan_juara_1"
                                       value="juara_1" @checked(old('perolehan', $prestasi->perolehan) === 'juara_1') required>
                                <span class="fw-bold text-dark small">Juara 1</span>
                            </div>
                            <span class="badge rounded-pill px-2 py-1 text-dark" style="background-color: #fef08a; font-size: 11px;">Emas</span>
                        </label>

                        <label class="p-2 px-3 rounded-3 border bg-white d-flex align-items-center justify-content-between cursor-pointer" for="perolehan_juara_2">
                            <div class="d-flex align-items-center gap-2">
                                <input class="form-check-input mt-0" type="radio" name="perolehan" id="perolehan_juara_2"
                                       value="juara_2" @checked(old('perolehan', $prestasi->perolehan) === 'juara_2') required>
                                <span class="fw-bold text-dark small">Juara 2</span>
                            </div>
                            <span class="badge rounded-pill px-2 py-1 text-secondary bg-light border" style="font-size: 11px;">Perak</span>
                        </label>

                        <label class="p-2 px-3 rounded-3 border bg-white d-flex align-items-center justify-content-between cursor-pointer" for="perolehan_juara_3">
                            <div class="d-flex align-items-center gap-2">
                                <input class="form-check-input mt-0" type="radio" name="perolehan" id="perolehan_juara_3"
                                       value="juara_3" @checked(old('perolehan', $prestasi->perolehan) === 'juara_3') required>
                                <span class="fw-bold text-dark small">Juara 3</span>
                            </div>
                            <span class="badge rounded-pill px-2 py-1 text-white" style="background-color: #cd7f32; font-size: 11px;">Perunggu</span>
                        </label>

                        <label class="p-2 px-3 rounded-3 border bg-white d-flex align-items-center justify-content-between cursor-pointer" for="perolehan_partisipasi">
                            <div class="d-flex align-items-center gap-2">
                                <input class="form-check-input mt-0" type="radio" name="perolehan" id="perolehan_partisipasi"
                                       value="partisipasi" @checked(old('perolehan', $prestasi->perolehan) === 'partisipasi') required>
                                <span class="fw-bold text-dark small">Partisipasi</span>
                            </div>
                            <span class="badge rounded-pill px-2 py-1 bg-light text-muted border" style="font-size: 11px;">Peserta</span>
                        </label>

                        <label class="p-2 px-3 rounded-3 border bg-white d-flex align-items-center justify-content-between cursor-pointer" for="perolehan_lainnya">
                            <div class="d-flex align-items-center gap-2">
                                <input class="form-check-input mt-0" type="radio" name="perolehan" id="perolehan_lainnya"
                                       value="lainnya" @checked(old('perolehan', $prestasi->perolehan) === 'lainnya') required>
                                <span class="fw-bold text-dark small">Peringkat lainnya</span>
                            </div>
                            <span class="badge rounded-pill px-2 py-1 bg-light text-muted border" style="font-size: 11px;">Khusus</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex flex-wrap align-items-center justify-content-center gap-3 pt-5 mt-4">
                <a href="{{ route('mahasiswa.prestasi.show', $prestasi) }}" class="btn btn-figma-cancel px-4">
                    Batal
                </a>
                <button type="submit" class="btn btn-figma-olive px-4 shadow-sm">
                    Upload & Simpan <i class="bi bi-cloud-arrow-up-fill ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
