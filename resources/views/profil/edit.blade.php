@extends('layout.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #1e293b;">
                @if($user->isMahasiswa())
                    Edit Profil Mahasiswa
                @elseif($user->isAdmin())
                    Edit Profil Admin
                @else
                    Edit Profil Pemimpin
                @endif
            </h4>
            <p class="text-muted mb-0 small">Perbarui data identitas Anda yang tersimpan di dalam sistem</p>
        </div>
        <div>
            <a href="{{ route('profil.show') }}" class="btn btn-outline-secondary rounded-3">
                <i class="bi bi-arrow-left me-1"></i> Batal / Kembali
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="content-card-title">
                        <i class="bi bi-pencil-square" style="color: var(--simpres-olive);"></i> Formulir Edit Profil
                    </h5>
                </div>

                <form action="{{ route('profil.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if($user->isMahasiswa())
                        <!-- Form Edit Profil Mahasiswa (Lap. hal. 105-106) -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nama Mahasiswa <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nomor Induk Mahasiswa (NIM) <span class="text-danger">*</span></label>
                                <input type="text" name="nim" class="form-control" value="{{ old('nim', $user->nim) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="prodi" class="form-label small fw-bold">Program Studi</label>
                                <select id="prodi" name="prodi" class="form-select">
                                    <option value="Sistem Informasi" @selected(old('prodi', $user->prodi) === 'Sistem Informasi')>Sistem Informasi</option>
                                    <option value="Matematika" @selected(old('prodi', $user->prodi) === 'Matematika')>Matematika</option>
                                    <option value="Ilmu Aktuaria" @selected(old('prodi', $user->prodi) === 'Ilmu Aktuaria')>Ilmu Aktuaria</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Fakultas</label>
                                <input type="text" name="fakultas" class="form-control" value="{{ old('fakultas', $user->fakultas ?? 'Fakultas Sains dan Teknologi') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Angkatan</label>
                                <input type="number" name="angkatan" class="form-control" value="{{ old('angkatan', $user->angkatan ?? 2024) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Gmail / Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nomor WhatsApp / HP</label>
                                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $user->no_hp) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Gender (Jenis Kelamin)</label>
                                <select name="gender" class="form-select">
                                    <option value="Perempuan" @selected(old('gender', $user->gender) === 'Perempuan')>Perempuan</option>
                                    <option value="Laki-laki" @selected(old('gender', $user->gender) === 'Laki-laki')>Laki-laki</option>
                                </select>
                            </div>
                        </div>

                    @elseif($user->isAdmin())
                        <!-- Form Edit Profil Admin (Lap. hal. 117-118) -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nama Admin <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Gmail / Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">No. Telepon / HP</label>
                                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $user->no_hp) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Gender (Jenis Kelamin)</label>
                                <select name="gender" class="form-select">
                                    <option value="Perempuan" @selected(old('gender', $user->gender) === 'Perempuan')>Perempuan</option>
                                    <option value="Laki-laki" @selected(old('gender', $user->gender) === 'Laki-laki')>Laki-laki</option>
                                </select>
                            </div>
                        </div>

                    @else
                        <!-- Form Edit Profil Pemimpin -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nama Pemimpin <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Email Resmi <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">No. Telepon</label>
                                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $user->no_hp) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="Perempuan" @selected(old('gender', $user->gender) === 'Perempuan')>Perempuan</option>
                                    <option value="Laki-laki" @selected(old('gender', $user->gender) === 'Laki-laki')>Laki-laki</option>
                                </select>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn btn-olive px-4 py-2 shadow-sm">
                            <i class="bi bi-save me-1"></i> Simpan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
