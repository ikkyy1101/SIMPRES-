@extends('layout.app')

@section('title', $mahasiswa->nama)

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h1 class="h4 mb-0">Riwayat Prestasi Mahasiswa</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Nama</dt><dd class="col-sm-9">{{ $mahasiswa->nama }}</dd>
                <dt class="col-sm-3">NIM</dt><dd class="col-sm-9">{{ $mahasiswa->nim }}</dd>
                <dt class="col-sm-3">Program Studi</dt><dd class="col-sm-9">{{ $mahasiswa->prodi ?: '-' }}</dd>
                <dt class="col-sm-3">Angkatan</dt><dd class="col-sm-9">{{ $mahasiswa->angkatan ?: '-' }}</dd>
                <dt class="col-sm-3">Email / No. HP</dt><dd class="col-sm-9">{{ $mahasiswa->email }} / {{ $mahasiswa->no_hp ?: '-' }}</dd>
            </dl>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Prestasi</th>
                        <th>Kategori / Tingkat</th>
                        <th>Perolehan</th>
                        <th>Tahun</th>
                        <th>Status</th>
                        <th>Skor</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($prestasi as $p)
                        <tr>
                            <td>{{ $p->nama_prestasi }}</td>
                            <td>{{ $p->jenisLabel() }}<br><small class="text-muted">{{ $p->tingkatLabel() }}</small></td>
                            <td>{{ $p->perolehanLabel() }}</td>
                            <td>{{ $p->tahun_perolehan }}</td>
                            <td>@include('partials.status-badge', ['prestasi' => $p])</td>
                            <td>{{ $p->skorTampil() }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.verifikasi.show', $p) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Mahasiswa ini belum memiliki data prestasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $prestasi->links() }}</div>
@endsection
