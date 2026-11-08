@props(['currentStep' => 1])

{{-- Stepper Progress Bar sesuai warna tema brand SIMPRES (Olive & Neutral) --}}
<div class="card border-0 rounded-4 shadow-sm mb-4 bg-white p-3">
    <div class="row g-2 align-items-center">
        <!-- Langkah 1: Input Data Prestasi -->
        <div class="col-md-4">
            <a href="{{ route('mahasiswa.prestasi.create') }}" class="text-decoration-none d-block">
                <div class="p-3 rounded-3 d-flex align-items-center gap-3 transition-all @if($currentStep === 1) text-white shadow-sm @elseif($currentStep > 1) bg-light text-dark @else bg-light text-muted opacity-75 @endif"
                     style="@if($currentStep === 1) background: linear-gradient(135deg, #535020 0%, #3e3b16 100%); @endif">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm"
                         style="width: 38px; height: 38px; @if($currentStep === 1) background: #ffffff; color: #535020; @elseif($currentStep > 1) background: rgba(83, 80, 32, 0.15); color: #535020; @else background: #e2e8f0; color: #94a3b8; @endif">
                        @if($currentStep > 1)
                            <i class="bi bi-check-lg fs-5"></i>
                        @else
                            1
                        @endif
                    </div>
                    <div>
                        <div class="small text-uppercase fw-semibold @if($currentStep === 1) text-white-50 @else text-muted @endif" style="font-size: 11px; letter-spacing: 0.5px;">Halaman 1</div>
                        <div class="fw-bold @if($currentStep === 1) text-white @else text-dark @endif" style="font-size: 14px;">Data Prestasi</div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Langkah 2: Upload Sertifikat -->
        <div class="col-md-4">
            <a href="{{ route('mahasiswa.upload.sertifikat') }}" class="text-decoration-none d-block">
                <div class="p-3 rounded-3 d-flex align-items-center gap-3 transition-all @if($currentStep === 2) text-white shadow-sm @elseif($currentStep > 2) bg-light text-dark @else bg-light text-muted opacity-75 @endif"
                     style="@if($currentStep === 2) background: linear-gradient(135deg, #535020 0%, #3e3b16 100%); @endif">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm"
                         style="width: 38px; height: 38px; @if($currentStep === 2) background: #ffffff; color: #535020; @elseif($currentStep > 2) background: rgba(83, 80, 32, 0.15); color: #535020; @else background: #e2e8f0; color: #94a3b8; @endif">
                        @if($currentStep > 2)
                            <i class="bi bi-check-lg fs-5"></i>
                        @else
                            2
                        @endif
                    </div>
                    <div>
                        <div class="small text-uppercase fw-semibold @if($currentStep === 2) text-white-50 @else text-muted @endif" style="font-size: 11px; letter-spacing: 0.5px;">Halaman 2</div>
                        <div class="fw-bold @if($currentStep === 2) text-white @else text-dark @endif" style="font-size: 14px;">Upload Sertifikat</div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Langkah 3: Upload Dokumentasi -->
        <div class="col-md-4">
            <a href="{{ route('mahasiswa.upload.dokumentasi') }}" class="text-decoration-none d-block">
                <div class="p-3 rounded-3 d-flex align-items-center gap-3 transition-all @if($currentStep === 3) text-white shadow-sm @elseif($currentStep > 3) bg-light text-dark @else bg-light text-muted opacity-75 @endif"
                     style="@if($currentStep === 3) background: linear-gradient(135deg, #535020 0%, #3e3b16 100%); @endif">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm"
                         style="width: 38px; height: 38px; @if($currentStep === 3) background: #ffffff; color: #535020; @else background: #e2e8f0; color: #94a3b8; @endif">
                        3
                    </div>
                    <div>
                        <div class="small text-uppercase fw-semibold @if($currentStep === 3) text-white-50 @else text-muted @endif" style="font-size: 11px; letter-spacing: 0.5px;">Halaman 3</div>
                        <div class="fw-bold @if($currentStep === 3) text-white @else text-dark @endif" style="font-size: 14px;">Upload Bukti Pendukung</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
