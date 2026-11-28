<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun | SIMPRES - UIN Imam Bonjol Padang</title>
    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --simpres-olive-dark: #3e3b16;
            --simpres-olive: #54511a;
            --simpres-olive-brand: #535020;
            --simpres-neutral: #dedee5;
            --simpres-figma-gradient: linear-gradient(90deg, #54511a 0%, #ded987 5.77%, #66621f 17.31%, #6a6621 21.15%, #bab33a 100%);
        }

        .top-accent-line {
            height: 3px;
            width: 100%;
            background: var(--simpres-figma-gradient);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 2000;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #100e0e;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-split-wrapper {
            width: 100%;
            min-height: 100vh;
            display: flex;
            background: #ffffff;
        }

        .auth-hero-pane {
            width: 45%;
            position: relative;
            background: linear-gradient(135deg, rgba(83, 80, 32, 0.94) 0%, rgba(62, 59, 22, 0.96) 100%),
                        url("{{ asset('images/auth/bg-login.png') }}") center center / cover no-repeat;
            padding: 56px 56px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #ffffff;
        }

        .auth-brand-logos {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .auth-logo-img {
            height: 72px;
            width: auto;
            object-fit: contain;
            background: #ffffff;
            border-radius: 12px;
            padding: 6px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
        }

        .auth-hero-content {
            margin-top: 40px;
            max-width: 560px;
        }

        .hero-title-first {
            font-size: 36px;
            font-weight: 800;
            line-height: 1.15;
            color: #fef08a;
            margin-bottom: 6px;
        }

        .hero-title-second {
            font-size: 36px;
            font-weight: 800;
            line-height: 1.15;
            color: #ffffff;
            margin-bottom: 12px;
        }

        .hero-title-faculty {
            font-size: 20px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.3;
            margin-bottom: 24px;
        }

        .auth-divider-line {
            width: 160px;
            height: 4px;
            background-color: #fef08a;
            border-radius: 4px;
            margin-bottom: 28px;
        }

        .auth-hero-desc {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.6;
        }

        .auth-hero-footer {
            margin-top: 40px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
        }

        .auth-form-pane {
            width: 55%;
            padding: 40px 56px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
            overflow-y: auto;
            position: relative;
            overflow-x: hidden;
        }

        .auth-form-inner {
            position: relative;
            z-index: 2;
            width: 100%;
        }

        .auth-corner-waves {
            position: absolute;
            right: 0;
            bottom: 0;
            width: clamp(220px, 20vw, 360px);
            height: clamp(160px, 15vw, 280px);
            pointer-events: none;
            z-index: 1;
            overflow: hidden;
            display: flex;
            align-items: flex-end;
            justify-content: flex-end;
        }

        .auth-corner-waves svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        .login-header-title {
            font-size: 32px;
            font-weight: 800;
            color: #100e0e;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .login-header-subtitle {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 24px;
        }

        .form-label-figma {
            font-size: 14px;
            font-weight: 700;
            color: #100e0e;
            margin-bottom: 6px;
            display: block;
        }

        .form-control-login,
        .form-select-login {
            background-color: #dedee5;
            border: 1.5px solid transparent;
            border-radius: 12px;
            min-height: 48px;
            font-size: 14px;
            padding: 10px 16px;
            color: #100e0e;
            transition: all 0.2s ease;
        }

        .form-control-login:focus,
        .form-select-login:focus {
            background-color: #ffffff;
            border-color: #535020;
            box-shadow: 0 0 0 0.2rem rgba(83, 80, 32, 0.15);
            outline: none;
        }

        .password-field-wrapper {
            position: relative;
        }

        .password-toggle-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            font-size: 18px;
            cursor: pointer;
            padding: 4px;
        }

        .password-toggle-btn:hover {
            color: #535020;
        }

        .btn-figma-submit {
            background-color: #535020;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            min-height: 50px;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.5px;
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(83, 80, 32, 0.25);
        }

        .btn-figma-submit:hover {
            background-color: #3e3b16;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(83, 80, 32, 0.35);
        }

        .footer-note-link {
            font-size: 14px;
            color: #64748b;
            text-align: center;
            margin-top: 20px;
        }

        .footer-note-link a {
            color: #54511a;
            font-weight: 700;
            text-decoration: none;
        }

        .footer-note-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 991.98px) {
            .auth-split-wrapper {
                flex-direction: column;
            }
            .auth-hero-pane {
                width: 100%;
                padding: 36px 20px;
                min-height: 280px;
            }
            .auth-form-pane {
                width: 100%;
                padding: 32px 20px;
            }
            .hero-title-first, .hero-title-second {
                font-size: 26px;
            }
            .login-header-title {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>
    <div class="top-accent-line"></div>
    <div class="auth-split-wrapper">
        <!-- Kolom Kiri -->
        <div class="auth-hero-pane">
            <div class="auth-brand-logos">
                <img src="{{ asset('images/auth/logo-uin.png') }}" alt="Logo UIN Imam Bonjol Padang" class="auth-logo-img">
                <img src="{{ asset('images/auth/logo-simpres.png') }}" alt="Logo SIMPRES" class="auth-logo-img">
            </div>

            <div class="auth-hero-content">
                <div class="hero-title-first">Pendaftaran Akun</div>
                <div class="hero-title-second">Mahasiswa SIMPRES</div>
                <div class="hero-title-faculty">Fakultas Sains Dan Teknologi UIN Imam Bonjol Padang</div>
                <div class="auth-divider-line"></div>
                <p class="auth-hero-desc">
                    Buat akun baru mahasiswa untuk mulai mengajukan, mengelola, dan memantau verifikasi prestasi akademik maupun non-akademik Anda.
                </p>
            </div>

            <div class="auth-hero-footer">
                &copy; {{ date('Y') }} FST UIN Imam Bonjol Padang &bull; SIMPRES Berbasis Web
            </div>
        </div>

        <!-- Kolom Kanan: Form Pendaftaran -->
        <div class="auth-form-pane">
            <div class="auth-form-inner">
                <h1 class="login-header-title">Daftar Akun Baru</h1>
                <p class="login-header-subtitle">Lengkapi data mahasiswa FST Anda untuk membuat akun SIMPRES</p>

                @if ($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 p-3 mb-3 small" style="background-color: #fee2e2; color: #991b1b;">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <strong>Terjadi Kesalahan:</strong>
                        </div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.process') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-7">
                            <label for="nama" class="form-label-figma">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                                   class="form-control form-control-login @error('nama') is-invalid @enderror"
                                   placeholder="Contoh: Alya Putri" required autofocus>
                        </div>

                        <div class="col-md-5">
                            <label for="nim" class="form-label-figma">NIM</label>
                            <input type="text" id="nim" name="nim" value="{{ old('nim') }}"
                                   class="form-control form-control-login @error('nim') is-invalid @enderror"
                                   placeholder="Contoh: 2417020001" required>
                        </div>

                        <div class="col-md-6">
                            <label for="username" class="form-label-figma">Username</label>
                            <input type="text" id="username" name="username" value="{{ old('username') }}"
                                   class="form-control form-control-login @error('username') is-invalid @enderror"
                                   placeholder="Contoh: alya24" required>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label-figma">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                   class="form-control form-control-login @error('email') is-invalid @enderror"
                                   placeholder="Contoh: alya@uinib.ac.id" required>
                        </div>

                        <div class="col-md-6">
                            <label for="prodi" class="form-label-figma">Program Studi</label>
                            <select id="prodi" name="prodi" class="form-select form-select-login @error('prodi') is-invalid @enderror" required>
                                <option value="">Pilih Program Studi</option>
                                @foreach ($prodiList as $programStudi)
                                    <option value="{{ $programStudi }}" @selected(old('prodi') === $programStudi)>
                                        {{ $programStudi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="angkatan" class="form-label-figma">Angkatan</label>
                            <input type="number" id="angkatan" name="angkatan" min="2000" max="{{ date('Y') + 1 }}"
                                   value="{{ old('angkatan', date('Y')) }}"
                                   class="form-control form-control-login @error('angkatan') is-invalid @enderror" required>
                        </div>

                        <div class="col-md-3">
                            <label for="no_hp" class="form-label-figma">No. HP</label>
                            <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}"
                                   class="form-control form-control-login @error('no_hp') is-invalid @enderror"
                                   placeholder="08xxxxxxxx">
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label-figma">Kata Sandi</label>
                            <div class="password-field-wrapper">
                                <input type="password" id="password" name="password" minlength="8"
                                       class="form-control form-control-login pe-5 @error('password') is-invalid @enderror"
                                       placeholder="Minimal 8 karakter" required>
                                <button type="button" class="password-toggle-btn" onclick="toggleField('password', 'iconPwd1')" aria-label="Lihat Kata Sandi">
                                    <i class="bi bi-eye" id="iconPwd1"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label-figma">Konfirmasi Kata Sandi</label>
                            <div class="password-field-wrapper">
                                <input type="password" id="password_confirmation" name="password_confirmation" minlength="8"
                                       class="form-control form-control-login pe-5"
                                       placeholder="Ulangi kata sandi" required>
                                <button type="button" class="password-toggle-btn" onclick="toggleField('password_confirmation', 'iconPwd2')" aria-label="Lihat Konfirmasi Kata Sandi">
                                    <i class="bi bi-eye" id="iconPwd2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-figma-submit mt-4">
                        DAFTAR AKUN
                    </button>

                    <div class="footer-note-link">
                        Sudah memiliki akun? <a href="{{ route('login') }}">Masuk Sekarang</a>
                    </div>

                    <div class="text-center mt-2">
                        <a href="{{ route('landing') }}" class="small text-muted text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="auth-corner-waves" aria-hidden="true">
            <svg viewBox="0 0 600 450" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M 0 450 C 180 410, 380 290, 600 50 L 600 450 Z" fill="#100E0E" />
                <path d="M 160 450 C 300 410, 460 300, 600 160 L 600 450 Z" fill="#535020" />
                <path d="M 320 450 C 420 420, 520 330, 600 270 L 600 450 Z" fill="#4E0909" />
            </svg>
        </div>
    </div>

    <script>
        function toggleField(inputId, iconId) {
            const pwdInput = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                pwdInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>

</html>
