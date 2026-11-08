@extends('layout.app')

@section('title', 'Upload Sertifikat Prestasi')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-2 gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: #100e0e; font-size: 24px; letter-spacing: -0.3px;">Upload Sertifikat</h4>
            <p class="text-muted mb-0 small">Unggah berkas bukti sertifikat prestasi dan tentukan peringkat perolehan juara mahasiswa.</p>
        </div>
        <div>
            <a href="{{ route('mahasiswa.prestasi.create') }}" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-medium">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Data Prestasi
            </a>
        </div>
    </div>

    <hr class="my-3 opacity-25" style="border-top: 2px solid #cbd5e1;">

    @include('mahasiswa.prestasi._stepper', ['currentStep' => 2])

    @if (!empty($draft['nama_prestasi']))
        <div class="alert border-0 rounded-3 p-3 mb-4 shadow-sm d-flex flex-wrap align-items-center justify-content-between gap-3"
             style="background-color: #f8fafc; border-left: 4px solid #535020 !important;">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center text-white"
                     style="width: 42px; height: 42px; background-color: #535020;">
                    <i class="bi bi-journal-bookmark-fill fs-5"></i>
                </div>
                <div>
                    <span class="badge bg-white text-dark border px-2 py-1 mb-1" style="font-size: 11px;">
                        {{ \App\Models\Prestasi::JENIS[$draft['jenis_prestasi'] ?? ''] ?? 'Prestasi' }} | {{ \App\Models\Prestasi::TINGKAT[$draft['tingkat_prestasi'] ?? ''] ?? '' }}
                    </span>
                    <h6 class="fw-bold mb-0 text-dark">{{ $draft['nama_prestasi'] }}</h6>
                    <small class="text-muted">Penyelenggara: {{ $draft['penyelenggara'] ?? '-' }} (Tahun {{ $draft['tahun_perolehan'] ?? date('Y') }})</small>
                </div>
            </div>
            <a href="{{ route('mahasiswa.prestasi.create') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-pencil me-1"></i> Ubah Data
            </a>
        </div>
    @endif

    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 p-md-5">
        <form action="{{ route('mahasiswa.upload.sertifikat.post') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @if (empty($draft['nama_prestasi']) && $prestasiList->isNotEmpty())
                <div class="mb-4 p-3 rounded-3 border" style="background-color: #f8fafc;">
                    <label for="id_prestasi" class="form-label small fw-bold text-dark">
                        Pilih Data Prestasi yang Ingin Dilengkapi Sertifikatnya:
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

            <div class="row g-4 align-items-stretch">
                <!-- Kolom Kiri: Box Upload Sertifikat Drag & Drop -->
                <div class="col-lg-6">
                    <label class="form-label fw-bold mb-2 text-secondary" style="font-size: 15px;">
                        Berkas Sertifikat Prestasi <span class="text-danger">*</span>
                    </label>

                    @if (!empty($draft['temp_sertifikat_name']))
                        <div class="p-3 mb-3 border rounded-3 d-flex align-items-center justify-content-between" style="background-color: #f1f2f6;">
                            <div class="d-flex align-items-center gap-2 text-truncate">
                                <i class="bi bi-file-earmark-check-fill text-success fs-4"></i>
                                <div>
                                    <strong class="d-block text-dark small text-truncate">{{ $draft['temp_sertifikat_name'] }}</strong>
                                    <small class="text-success"><i class="bi bi-check-circle-fill me-1"></i> File siap dalam draft</small>
                                </div>
                            </div>
                            <span class="badge bg-secondary-subtle text-secondary small">Ganti jika perlu</span>
                        </div>
                    @endif

                    <div class="dropzone-figma p-4 text-center d-flex flex-column align-items-center justify-content-center position-relative h-100"
                         style="min-height: 280px;" id="dropzone-box">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 72px; height: 72px; background: rgba(83, 80, 32, 0.1); color: #535020;">
                            <i class="bi bi-cloud-arrow-up-fill" style="font-size: 38px;"></i>
                        </div>

                        <h6 class="fw-bold mb-1 text-dark" style="font-size: 16px;">Pilih file atau drag & drop</h6>
                        <p class="small text-muted mb-3" style="max-width: 320px;">
                            (PDF, DOC, DOCX, JPG, PNG) &bull; Maksimal 5 MB
                        </p>

                        <div class="d-inline-block">
                            <input type="file" id="sertifikat" name="sertifikat" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                   class="form-control form-control-sm @error('sertifikat') is-invalid @enderror"
                                   @if(empty($draft['temp_sertifikat_path'])) required @endif
                                   onchange="handleFileSelect(this)">
                        </div>

                        <div id="file-info-preview" class="d-none mt-3 p-2 rounded-3 bg-white border text-start small w-100">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="fw-semibold text-truncate me-2" id="selected-file-name">Nama File</span>
                                <span class="badge bg-light text-dark border" id="selected-file-size">0 KB</span>
                            </div>
                        </div>

                        @error('sertifikat')
                            <div class="text-danger small mt-2 fw-semibold">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Kolom Kanan: Peringkat Radio List -->
                <div class="col-lg-6">
                    <label class="form-label fw-bold mb-2 text-secondary" style="font-size: 15px;">
                        Peringkat : <span class="text-danger">*</span>
                    </label>

                    <div class="vstack gap-2" style="background-color: #f8fafc; padding: 18px; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <!-- Juara 1 -->
                        <label class="p-3 rounded-3 border bg-white d-flex align-items-center justify-content-between cursor-pointer"
                               for="perolehan_juara_1">
                            <div class="d-flex align-items-center gap-3">
                                <input class="form-check-input mt-0" type="radio" name="perolehan" id="perolehan_juara_1"
                                       value="juara_1" @checked(old('perolehan', $draft['perolehan'] ?? 'juara_1') === 'juara_1') required>
                                <span class="fw-bold text-dark" style="font-size: 15px;">Juara 1</span>
                            </div>
                            <span class="badge rounded-pill px-3 py-2 text-dark" style="background-color: #fef08a; font-size: 12px;">
                                <i class="bi bi-trophy-fill text-warning me-1"></i> Emas
                            </span>
                        </label>

                        <!-- Juara 2 -->
                        <label class="p-3 rounded-3 border bg-white d-flex align-items-center justify-content-between cursor-pointer"
                               for="perolehan_juara_2">
                            <div class="d-flex align-items-center gap-3">
                                <input class="form-check-input mt-0" type="radio" name="perolehan" id="perolehan_juara_2"
                                       value="juara_2" @checked(old('perolehan', $draft['perolehan'] ?? '') === 'juara_2') required>
                                <span class="fw-bold text-dark" style="font-size: 15px;">Juara 2</span>
                            </div>
                            <span class="badge rounded-pill px-3 py-2 text-secondary bg-light border" style="font-size: 12px;">
                                <i class="bi bi-award-fill text-secondary me-1"></i> Perak
                            </span>
                        </label>

                        <!-- Juara 3 -->
                        <label class="p-3 rounded-3 border bg-white d-flex align-items-center justify-content-between cursor-pointer"
                               for="perolehan_juara_3">
                            <div class="d-flex align-items-center gap-3">
                                <input class="form-check-input mt-0" type="radio" name="perolehan" id="perolehan_juara_3"
                                       value="juara_3" @checked(old('perolehan', $draft['perolehan'] ?? '') === 'juara_3') required>
                                <span class="fw-bold text-dark" style="font-size: 15px;">Juara 3</span>
                            </div>
                            <span class="badge rounded-pill px-3 py-2 text-white" style="background-color: #cd7f32; font-size: 12px;">
                                <i class="bi bi-award me-1"></i> Perunggu
                            </span>
                        </label>

                        <!-- Partisipasi -->
                        <label class="p-3 rounded-3 border bg-white d-flex align-items-center justify-content-between cursor-pointer"
                               for="perolehan_partisipasi">
                            <div class="d-flex align-items-center gap-3">
                                <input class="form-check-input mt-0" type="radio" name="perolehan" id="perolehan_partisipasi"
                                       value="partisipasi" @checked(old('perolehan', $draft['perolehan'] ?? '') === 'partisipasi') required>
                                <span class="fw-bold text-dark" style="font-size: 15px;">Partisipasi</span>
                            </div>
                            <span class="badge rounded-pill px-3 py-2 bg-light text-muted border" style="font-size: 12px;">
                                <i class="bi bi-person-check-fill me-1"></i> Peserta
                            </span>
                        </label>

                        <!-- Peringkat lainnya -->
                        <label class="p-3 rounded-3 border bg-white d-flex align-items-center justify-content-between cursor-pointer"
                               for="perolehan_lainnya">
                            <div class="d-flex align-items-center gap-3">
                                <input class="form-check-input mt-0" type="radio" name="perolehan" id="perolehan_lainnya"
                                       value="lainnya" @checked(old('perolehan', $draft['perolehan'] ?? '') === 'lainnya') required>
                                <span class="fw-bold text-dark" style="font-size: 15px;">Peringkat lainnya</span>
                            </div>
                            <span class="badge rounded-pill px-3 py-2 bg-light text-muted border" style="font-size: 12px;">
                                Harapan / Khusus
                            </span>
                        </label>
                    </div>

                    @error('perolehan')
                        <div class="text-danger small mt-2 fw-semibold">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Bagian Bawah: Keterangan (opsional) -->
                <div class="col-12 mt-4">
                    <label for="keterangan" class="form-label fw-bold mb-2 text-secondary" style="font-size: 15px;">
                        Keterangan <span class="text-muted fw-normal">(opsional)</span>
                    </label>
                    <textarea id="keterangan" name="keterangan" rows="3" maxlength="1000"
                              class="form-control form-control-figma p-3"
                              placeholder="Masukkan keterangan tentang sertifikat">{{ old('keterangan', $draft['keterangan'] ?? '') }}</textarea>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex flex-wrap align-items-center justify-content-center gap-3 pt-5 mt-4">
                <a href="{{ route('mahasiswa.prestasi.create') }}" class="btn btn-figma-cancel px-4">
                    Batal
                </a>
                <button type="submit" class="btn btn-figma-olive px-4 shadow-sm">
                    Upload & Lanjut <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function handleFileSelect(input) {
        const preview = document.getElementById('file-info-preview');
        const nameEl = document.getElementById('selected-file-name');
        const sizeEl = document.getElementById('selected-file-size');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            nameEl.textContent = file.name;
            const sizeInKB = (file.size / 1024).toFixed(1);
            sizeEl.textContent = sizeInKB > 1024 ? (sizeInKB / 1024).toFixed(2) + ' MB' : sizeInKB + ' KB';
            preview.classList.remove('d-none');
        } else {
            preview.classList.add('d-none');
        }
    }
</script>
@endpush
@endsection
