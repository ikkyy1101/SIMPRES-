@php $m = $mahasiswa ?? null; @endphp

<form action="{{ $action }}" method="POST">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label" for="nama">Nama <span class="text-danger">*</span></label>
                    <input type="text" id="nama" name="nama" maxlength="150" value="{{ old('nama', $m->nama ?? '') }}"
                        class="form-control @error('nama') is-invalid @enderror" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="nim">NIM <span class="text-danger">*</span></label>
                    <input type="text" id="nim" name="nim" maxlength="30" value="{{ old('nim', $m->nim ?? '') }}"
                        class="form-control @error('nim') is-invalid @enderror" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="prodi">Program Studi <span class="text-danger">*</span></label>
                    <select id="prodi" name="prodi" class="form-select @error('prodi') is-invalid @enderror" required>
                        <option value="">Pilih program studi</option>
                        @foreach (($prodiList ?? \App\Models\User::PROGRAM_STUDI) as $programStudi)
                            <option value="{{ $programStudi }}" @selected(old('prodi', $m->prodi ?? '') === $programStudi)>
                                {{ $programStudi }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="angkatan">Angkatan <span class="text-danger">*</span></label>
                    <input type="number" id="angkatan" name="angkatan" min="2000" max="{{ now()->year }}"
                        value="{{ old('angkatan', $m->angkatan ?? '') }}"
                        class="form-control @error('angkatan') is-invalid @enderror" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="no_hp">No. HP</label>
                    <input type="text" id="no_hp" name="no_hp" maxlength="20" value="{{ old('no_hp', $m->no_hp ?? '') }}"
                        class="form-control @error('no_hp') is-invalid @enderror">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
                    <input type="text" id="username" name="username" maxlength="50"
                        value="{{ old('username', $m->username ?? '') }}"
                        class="form-control @error('username') is-invalid @enderror" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" id="email" name="email" maxlength="255" value="{{ old('email', $m->email ?? '') }}"
                        class="form-control @error('email') is-invalid @enderror" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="password">
                        Password @if ($m) <small class="text-muted">(kosongkan jika tidak diubah)</small> @else <span class="text-danger">*</span> @endif
                    </label>
                    <input type="password" id="password" name="password" minlength="8"
                        class="form-control @error('password') is-invalid @enderror" @required(!$m)
                        autocomplete="new-password">
                </div>
            </div>
        </div>
        <div class="card-footer d-flex gap-2">
            <button class="btn btn-primary">{{ $submitLabel }}</button>
            <a href="{{ $m ? route('admin.mahasiswa.show', $m) : route('admin.mahasiswa.index') }}"
                class="btn btn-outline-secondary">Batal</a>
        </div>
    </div>
</form>
