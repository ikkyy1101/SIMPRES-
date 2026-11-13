@extends('layout.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #1e293b;">
                @if($user->isMahasiswa())
                    Profil Mahasiswa
                @elseif($user->isAdmin())
                    Profil Admin
                @else
                    Profil Pemimpin Fakultas
                @endif
            </h4>

            <p class="text-muted mb-0 small">
                Kelola Informasi Akun dan Identitas Anda dalam SIMPRES
            </p>
        </div>

        <div>
            <a href="{{ route('profil.edit') }}" class="btn btn-olive shadow-sm">
                <i class="bi bi-pencil-square me-1"></i> Edit Profil
            </a>
        </div>
    </div>

    <div class="row g-4">

        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="content-card text-center p-4">

                <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow"
                    style="width: 100px; height: 100px; font-size: 36px; background: linear-gradient(135deg, var(--simpres-olive), #3e462c);">

                    {{ strtoupper(substr($user->nama ?? $user->username, 0, 1)) }}

                </div>

                <h5 class="fw-bold mb-1">
                    {{ $user->nama }}
                </h5>

                <div class="badge bg-light text-dark border px-3 py-1 mb-3">
                    {{ ucfirst($user->role) }}
                </div>

                <div class="list-group list-group-flush text-start small mt-3">

                    <div class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Username:</span>
                        <span class="fw-semibold">{{ $user->username }}</span>
                    </div>

                    <div class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Email:</span>
                        <span class="fw-semibold">{{ $user->email }}</span>
                    </div>

                    <div class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">No. HP:</span>
                        <span class="fw-semibold">{{ $user->no_hp ?? '-' }}</span>
                    </div>

                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="col-lg-8">

            <div class="content-card">

                <div class="content-card-header">
                    <h5 class="content-card-title">
                        <i class="bi bi-person-lines-fill"
                            style="color: var(--simpres-olive);"></i>
                        Identitas Lengkap
                    </h5>
                </div>

                @if($user->isMahasiswa())

                    <!-- Identitas Mahasiswa -->
                    <div class="row g-3">

                        <div class="col-sm-6">
                            <label class="text-muted small">Nama Mahasiswa</label>
                            <div class="fw-bold fs-6">{{ $user->nama }}</div>
                        </div>

                        <div class="col-sm-6">
                            <label class="text-muted small">
                                Nomor Induk Mahasiswa (NIM)
                            </label>
                            <div class="fw-bold fs-6">{{ $user->nim ?? '-' }}</div>
                        </div>

                        <div class="col-sm-6">
                            <label class="text-muted small">Program Studi</label>
                            <div class="fw-bold fs-6">
                                {{ $user->prodi ?? 'Sistem Informasi' }}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label class="text-muted small">Fakultas</label>
                            <div class="fw-bold fs-6">
                                {{ $user->fakultas ?? 'Fakultas Sains dan Teknologi' }}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label class="text-muted small">Angkatan</label>
                            <div class="fw-bold fs-6">
                                {{ $user->angkatan ?? '2024' }}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label class="text-muted small">
                                Alamat Email / Gmail
                            </label>
                            <div class="fw-bold fs-6">
                                {{ $user->email }}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label class="text-muted small">
                                Jenis Kelamin (Gender)
                            </label>
                            <div class="fw-bold fs-6">
                                {{ $user->gender ?? 'Perempuan' }}
                            </div>
                        </div>

                    </div>

                @elseif($user->isAdmin())

                    <!-- Identitas Admin -->
                    <div class="row g-3">

                        <div class="col-sm-6">
                            <label class="text-muted small">Nama Admin</label>
                            <div class="fw-bold fs-6">{{ $user->nama }}</div>
                        </div>

                        <div class="col-sm-6">
                            <label class="text-muted small">Username</label>
                            <div class="fw-bold fs-6">{{ $user->username }}</div>
                        </div>

                        <div class="col-sm-6">
                            <label class="text-muted small">
                                Alamat Email / Gmail
                            </label>
                            <div class="fw-bold fs-6">{{ $user->email }}</div>
                        </div>

                        <div class="col-sm-6">
                            <label class="text-muted small">Nomor Telepon</label>
                            <div class="fw-bold fs-6">
                                {{ $user->no_hp ?? '-' }}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label class="text-muted small">
                                Jenis Kelamin (Gender)
                            </label>
                            <div class="fw-bold fs-6">
                                {{ $user->gender ?? 'Perempuan' }}
                            </div>
                        </div>

                    </div>

                @else

                    <!-- Identitas Pemimpin Fakultas -->
                    <div class="row g-3">

                        <div class="col-sm-6">
                            <label class="text-muted small">Nama Pemimpin</label>
                            <div class="fw-bold fs-6">{{ $user->nama }}</div>
                        </div>

                        <div class="col-sm-6">
                            <label class="text-muted small">Jabatan</label>
                            <div class="fw-bold fs-6">
                                {{ $user->pimpinan?->jabatan ?? 'Dekan Fakultas Sains dan Teknologi' }}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label class="text-muted small">Username</label>
                            <div class="fw-bold fs-6">
                                {{ $user->username }}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label class="text-muted small">Email Resmi</label>
                            <div class="fw-bold fs-6">
                                {{ $user->email }}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label class="text-muted small">Nomor Telepon</label>
                            <div class="fw-bold fs-6">
                                {{ $user->no_hp ?? '-' }}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label class="text-muted small">Gender</label>
                            <div class="fw-bold fs-6">
                                {{ $user->gender ?? 'Perempuan' }}
                            </div>
                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>
</div>
@endsection