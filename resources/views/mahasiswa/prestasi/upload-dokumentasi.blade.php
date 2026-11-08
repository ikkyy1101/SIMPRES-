@extends('layout.app')

@section('title', 'Upload Bukti Pendukung')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-2 gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: #100e0e; font-size: 24px; letter-spacing: -0.3px;">Upload Bukti Pendukung</h4>
            <p class="text-muted mb-0 small">Unggah bukti foto suasana kegiatan, penyerahan piala, medali, atau dokumentasi kejuaraan.</p>
        </div>
        <div>
            <a href="{{ route('mahasiswa.upload.sertifikat') }}" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-medium">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Upload Sertifikat
            </a>
        </div>
    </div>

    <hr class="my-3 opacity-25" style="border-top: 2px solid #cbd5e1;">

    @include('mahasiswa.prestasi._stepper', ['currentStep' => 3])

    @if (!empty($draft['nama_prestasi']))
        <div class="alert border-0 rounded-3 p-3 mb-4 shadow-sm" style="background-color: #f8fafc; border-left: 4px solid #535020 !important;">
            <div class="row align-items-center g-3">
                <div class="col-md-7">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-white flex-shrink-0"
                             style="width: 42px; height: 42px; background-color: #535020;">
                            <i class="bi bi-award-fill fs-5"></i>
                        </div>
                        <div>
                            <span class="badge bg-white text-dark border px-2 py-1 mb-1" style="font-size: 11px;">
                                {{ \App\Models\Prestasi::JENIS[$draft['jenis_prestasi'] ?? ''] ?? 'Prestasi' }} | {{ \App\Models\Prestasi::TINGKAT[$draft['tingkat_prestasi'] ?? ''] ?? '' }}
                            </span>
                            <h6 class="fw-bold mb-0 text-dark">{{ $draft['nama_prestasi'] }}</h6>
                            <small class="text-muted">Hasil: <strong>{{ \App\Models\Prestasi::PEROLEHAN[$draft['perolehan'] ?? ''] ?? 'Juara' }}</strong> | Penyelenggara: {{ $draft['penyelenggara'] ?? '-' }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 text-md-end">
                    <span class="small text-muted d-block mb-1">Sertifikat Terlampir:</span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill small">
                        <i class="bi bi-file-earmark-check me-1"></i> {{ $draft['temp_sertifikat_name'] ?? 'Berkas Sertifikat Siap' }}
                    </span>
                </div>
            </div>
        </div>
    @endif

    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 p-md-5">
        <form action="{{ route('mahasiswa.upload.dokumentasi.post') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @if (empty($draft['nama_prestasi']) && $prestasiList->isNotEmpty())
                <div class="mb-4 p-3 rounded-3 border" style="background-color: #f8fafc;">
                    <label for="id_prestasi" class="form-label small fw-bold text-dark">
                        Pilih Data Prestasi yang Ingin Ditambahkan Bukti Pendukungnya:
                    </label>
                    <select name="id_prestasi" id="id_prestasi" class="form-select form-select-figma @error('id_prestasi') is-invalid @enderror">
                        <option value="">pilih data prestasi yang telah terdaftar</option>
                        @foreach ($prestasiList as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->nama_prestasi }} ({{ $item->tahun_perolehan }}) - Status: {{ $item->statusLabel() }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Area Box Upload Cloud -->
            <div class="dropzone-figma p-4 p-md-5 text-center d-flex flex-column align-items-center justify-content-center mb-4 position-relative"
                 style="min-height: 260px;">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width: 76px; height: 76px; background: rgba(83, 80, 32, 0.1); color: #535020;">
                    <i class="bi bi-cloud-arrow-up-fill" style="font-size: 40px;"></i>
                </div>

                <h6 class="fw-bold mb-1 text-dark" style="font-size: 17px;">Pilih file atau drag & drop</h6>
                <p class="small text-muted mb-3" style="max-width: 400px;">
                    (PDF, DOC, DOCX, JPG, PNG) &bull; Unggah foto dokumentasi kegiatan (Maksimal 10 berkas, ukuran per berkas maksimal 5 MB)
                </p>

                <div id="dokumentasi-list-container" class="vstack gap-3 w-100 mt-2 text-start" style="max-width: 760px;">
                    <!-- Baris Pertama Dokumentasi -->
                    <div class="p-3 rounded-3 bg-white border doc-row shadow-sm">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-5">
                                <label class="form-label small fw-semibold text-muted d-block mb-1">Pilih Foto atau Berkas:</label>
                                <input type="file" class="form-control form-control-sm border" name="dokumentasi[0]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted d-block mb-1">Keterangan Foto (Opsional):</label>
                                <input type="text" class="form-control form-control-sm border" maxlength="1000" name="keterangan_dokumentasi[0]" placeholder="Contoh: Foto penyerahan penghargaan di podium">
                            </div>
                            <div class="col-md-1 text-center pt-md-3">
                                <span class="badge bg-light text-muted border">Foto 1</span>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" id="btn-tambah-baris-doc" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-2 fw-semibold mt-3">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Foto Dokumentasi Lainnya
                </button>
            </div>

            <!-- Bagian Keterangan (opsional) -->
            <div class="mt-4">
                <label for="keterangan_umum" class="form-label fw-bold mb-2 text-secondary" style="font-size: 15px;">
                    Keterangan <span class="text-muted fw-normal">(opsional)</span>
                </label>
                <textarea id="keterangan_umum" name="keterangan_umum" rows="3" maxlength="1000"
                          class="form-control form-control-figma p-3"
                          placeholder="Masukkan keterangan tentang dokumentasi atau informasi pendukung kegiatan">{{ old('keterangan_umum', $draft['keterangan_umum'] ?? '') }}</textarea>
            </div>

            <div class="alert border-0 rounded-3 p-3 mt-4 small d-flex align-items-center gap-2" style="background-color: #f8fafc; color: #475569;">
                <i class="bi bi-info-circle-fill fs-5 text-primary"></i>
                <div>
                    Dokumentasi bersifat opsional. Jika Anda belum memiliki dokumentasi foto saat ini, Anda dapat langsung menekan tombol <strong>Upload & Simpan</strong> di bawah.
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex flex-wrap align-items-center justify-content-center gap-3 pt-5 mt-4">
                <a href="{{ route('mahasiswa.upload.sertifikat') }}" class="btn btn-figma-cancel px-4">
                    Batal
                </a>
                <button type="submit" class="btn btn-figma-olive px-4 shadow-sm">
                    Upload & Simpan <i class="bi bi-cloud-arrow-up-fill ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    (function() {
        const container = document.getElementById('dokumentasi-list-container');
        const btnTambah = document.getElementById('btn-tambah-baris-doc');
        const maksBerkas = 10;
        let counter = 1;

        if (btnTambah) {
            btnTambah.addEventListener('click', function() {
                const totalBaris = container.querySelectorAll('.doc-row').length;
                if (totalBaris >= maksBerkas) {
                    alert('Batas maksimal dokumentasi adalah 10 berkas.');
                    return;
                }

                const index = counter++;
                const barisBaru = document.createElement('div');
                barisBaru.className = 'p-3 rounded-3 bg-white border doc-row shadow-sm position-relative';
                barisBaru.innerHTML = `
                    <div class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold text-muted d-block mb-1">Pilih Foto atau Berkas:</label>
                            <input type="file" class="form-control form-control-sm border" name="dokumentasi[${index}]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted d-block mb-1">Keterangan Foto (Opsional):</label>
                            <input type="text" class="form-control form-control-sm border" maxlength="1000" name="keterangan_dokumentasi[${index}]" placeholder="Keterangan singkat dokumentasi">
                        </div>
                        <div class="col-md-1 text-center pt-md-3">
                            <button type="button" class="btn btn-outline-danger btn-sm rounded-circle" title="Hapus" onclick="this.closest('.doc-row').remove()">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(barisBaru);
            });
        }
    })();
</script>
@endpush
@endsection
