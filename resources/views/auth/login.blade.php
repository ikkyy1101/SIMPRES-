<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | SIMPRES - UIN Imam Bonjol Padang</title>
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
            --simpres-neutral-input: #f1f2f6;
            /* 5-Color Stop Figma Gradient (#54511A, #DED987, #66621F, #6A6621, #BAB33A) */
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

        /* Sisi Kiri: Banner Hero Kampus sesuai Figma Node 125:209 */
        .auth-hero-pane {
            width: 50%;
            position: relative;
            background: linear-gradient(135deg, rgba(83, 80, 32, 0.94) 0%, rgba(62, 59, 22, 0.96) 100%),
                        url("{{ asset('images/auth/bg-login.png') }}") center center / cover no-repeat;
            padding: 56px 64px;
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
            max-width: 600px;
        }

        .hero-title-first {
            font-size: 38px;
            font-weight: 800;
            line-height: 1.15;
            color: #fef08a;
            margin-bottom: 6px;
        }

        .hero-title-second {
            font-size: 38px;
            font-weight: 800;
            line-height: 1.15;
            color: #ffffff;
            margin-bottom: 12px;
        }

        .hero-title-faculty {
            font-size: 22px;
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

        /* Sisi Kanan: Form Login sesuai Figma Node 125:209 */
        .auth-form-pane {
            width: 50%;
            padding: 48px 68px;
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

        /* Ornamen Lengkungan 3 Warna di Sudut Kanan Bawah Sesuai Desain Figma Node 32:78 */
        .auth-corner-waves {
            position: absolute;
            right: 0;
            bottom: 0;
            width: clamp(260px, 24vw, 420px);
            height: clamp(200px, 18vw, 320px);
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
            font-size: 36px;
            font-weight: 800;
            color: #100e0e;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .login-header-subtitle {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 28px;
        }

        .form-label-figma {
            font-size: 15px;
            font-weight: 700;
            color: #100e0e;
            margin-bottom: 8px;
            display: block;
        }

        .form-control-login {
            background-color: #dedee5;
            border: 1.5px solid transparent;
            border-radius: 12px;
            min-height: 52px;
            font-size: 15px;
            padding: 12px 18px;
            color: #100e0e;
            transition: all 0.2s ease;
        }

        .form-control-login:focus {
            background-color: #ffffff;
            border-color: #535020;
            box-shadow: 0 0 0 0.2rem rgba(83, 80, 32, 0.15);
            outline: none;
        }

        .form-select-login {
            background-color: #dedee5;
            border: 1.5px solid transparent;
            border-radius: 12px;
            min-height: 52px;
            font-size: 15px;
            font-weight: 600;
            padding: 12px 18px;
            color: #100e0e;
            transition: all 0.2s ease;
        }

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
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            font-size: 20px;
            cursor: pointer;
            padding: 4px;
        }

        .password-toggle-btn:hover {
            color: #535020;
        }

        /* Tombol Login sesuai Figma Node 14:35 */
        .btn-figma-submit {
            background-color: #535020;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            min-height: 52px;
            font-size: 18px;
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

        /* Pembatas ATAU sesuai Figma Node 38:113 */
        .separator-atau-wrapper {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 24px 0;
        }

        .separator-atau-line {
            flex: 1;
            height: 1px;
            background-color: #cbd5e1;
        }

        .separator-atau-text {
            font-size: 14px;
            font-weight: 600;
            color: #64748b;
        }

        /* Tombol SSO Alternatif sesuai Figma */
        .btn-sso-figma {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            min-height: 46px;
            font-size: 14px;
            font-weight: 600;
            color: #100e0e;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 10px;
        }

        .btn-sso-figma:hover {
            background-color: #f8fafc;
            border-color: #94a3b8;
            color: #100e0e;
        }

        .sso-icon-brand {
            font-size: 18px;
        }

        .footer-note-link {
            font-size: 14px;
            color: #64748b;
            text-align: center;
            margin-top: 24px;
        }

        .footer-note-link a {
            color: #54511a;
            font-weight: 700;
            text-decoration: none;
        }

        .footer-note-link a:hover {
            text-decoration: underline;
        }

        .quick-login-btn {
            border: 1px solid #e2e8f0 !important;
            transition: all 0.2s ease;
        }

        .quick-login-btn:hover {
            border-color: #535020 !important;
            background-color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(83, 80, 32, 0.15) !important;
        }

        /* Responsif Mobile & Tablet */
        @media (max-width: 991.98px) {
            .auth-split-wrapper {
                flex-direction: column;
            }
            .auth-hero-pane {
                width: 100%;
                padding: 36px 20px;
                min-height: 320px;
            }
            .auth-form-pane {
                width: 100%;
                padding: 36px 20px;
            }
            .hero-title-first, .hero-title-second {
                font-size: 26px;
            }
            .login-header-title {
                font-size: 28px;
            }
            .auth-corner-waves {
                width: 200px;
                height: 150px;
                opacity: 0.9;
            }
        }
    </style>
</head>

<body>
    <div class="top-accent-line"></div>
    <div class="auth-split-wrapper">
        <!-- Kolom Kiri: Hero Banner Informasi & Identitas Kampus -->
        <div class="auth-hero-pane">
            <div class="auth-brand-logos">
                <img src="{{ asset('images/auth/logo-uin.png') }}" alt="Logo UIN Imam Bonjol Padang" class="auth-logo-img">
                <img src="{{ asset('images/auth/logo-simpres.png') }}" alt="Logo SIMPRES" class="auth-logo-img">
            </div>

            <div class="auth-hero-content">
                <div class="hero-title-first">Sistem Informasi</div>
                <div class="hero-title-second">Manajemen Prestasi Mahasiswa</div>
                <div class="hero-title-faculty">Fakultas Sains Dan Teknologi Berbasis Web</div>
                <div class="auth-divider-line"></div>
                <p class="auth-hero-desc">
                    Silakan masuk menggunakan identitas resmi Anda. Pastikan peran pengguna sesuai dengan hak akses yang terdaftar di Fakultas Sains dan Teknologi UIN Imam Bonjol Padang.
                </p>
            </div>

            <div class="auth-hero-footer">
                &copy; {{ date('Y') }} FST UIN Imam Bonjol Padang &bull; SIMPRES Berbasis Web
            </div>
        </div>

        <!-- Kolom Kanan: Form Login sesuai Figma 125:209 -->
        <div class="auth-form-pane">
            <div class="auth-form-inner">
                <h1 class="login-header-title">Selamat Datang</h1>
            <p class="login-header-subtitle">Silakan masukkan informasi akun Anda untuk masuk ke sistem</p>

            <!-- Bagian Login Cepat (Akun Demo) bertema Figma -->
            <div class="p-3 rounded-4 mb-4" style="background-color: #f1f2f6; border: 1.5px dashed #cbd5e1;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge text-white px-2 py-1 rounded-2" style="background-color: #535020; font-size: 11px;">
                            <i class="bi bi-lightning-charge-fill text-warning me-1"></i> LOGIN CEPAT
                        </span>
                        <span class="fw-bold text-dark" style="font-size: 13px;">Uji Coba Demo</span>
                    </div>
                    <small class="text-muted" style="font-size: 11px;">1-Klik Masuk</small>
                </div>
                <p class="text-muted small mb-3" style="font-size: 12px; line-height: 1.4;">
                    Pilih akun demo di bawah untuk langsung masuk tanpa perlu mengetik kredensial:
                </p>

                <div class="row g-2">
                    <!-- Demo Mahasiswa -->
                    <div class="col-12 col-md-4">
                        <form action="{{ route('login.quick', ['role' => 'mahasiswa']) }}" method="POST">
                            @csrf
                            <button type="submit" class="quick-login-btn w-100 text-start p-2 rounded-3"
                                    style="background: #ffffff; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                         style="width: 32px; height: 32px; background: rgba(83, 80, 32, 0.12); color: #535020;">
                                        <i class="bi bi-mortarboard-fill"></i>
                                    </div>
                                    <div class="text-truncate">
                                        <strong class="d-block text-dark small" style="font-size: 12px;">Mahasiswa</strong>
                                        <small class="text-muted text-truncate d-block" style="font-size: 10px;">Alya putri</small>
                                    </div>
                                </div>
                            </button>
                        </form>
                    </div>

                    <!-- Demo Admin -->
                    <div class="col-12 col-md-4">
                        <form action="{{ route('login.quick', ['role' => 'admin']) }}" method="POST">
                            @csrf
                            <button type="submit" class="quick-login-btn w-100 text-start p-2 rounded-3"
                                    style="background: #ffffff; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                         style="width: 32px; height: 32px; background: rgba(83, 80, 32, 0.12); color: #535020;">
                                        <i class="bi bi-person-badge-fill"></i>
                                    </div>
                                    <div class="text-truncate">
                                        <strong class="d-block text-dark small" style="font-size: 12px;">Admin</strong>
                                        <small class="text-muted text-truncate d-block" style="font-size: 10px;">Aura Maylia</small>
                                    </div>
                                </div>
                            </button>
                        </form>
                    </div>

                    <!-- Demo Pemimpin -->
                    <div class="col-12 col-md-4">
                        <form action="{{ route('login.quick', ['role' => 'pimpinan']) }}" method="POST">
                            @csrf
                            <button type="submit" class="quick-login-btn w-100 text-start p-2 rounded-3"
                                    style="background: #ffffff; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                         style="width: 32px; height: 32px; background: rgba(83, 80, 32, 0.12); color: #535020;">
                                        <i class="bi bi-graph-up-arrow"></i>
                                    </div>
                                    <div class="text-truncate">
                                        <strong class="d-block text-dark small" style="font-size: 12px;">Pemimpin</strong>
                                        <small class="text-muted text-truncate d-block" style="font-size: 10px;">Selvia P.</small>
                                    </div>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-3 p-3 mb-4 small" style="background-color: #fee2e2; color: #991b1b;">
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

            <form action="{{ route('login.process') }}" method="POST">
                @csrf

                <!-- Pilihan Peran (DAFTAR / MASUK SEBAGAI) sesuai Figma Node 12:7 & 10:5 -->
                <div class="mb-3">
                    <label for="role" class="form-label-figma">MASUK SEBAGAI</label>
                    <div class="position-relative">
                        <select name="role" id="role" class="form-select form-select-login @error('role') is-invalid @enderror" required>
                            <option value="mahasiswa" @selected(request('role') === 'mahasiswa' || old('role') === 'mahasiswa')>Mahasiswa</option>
                            <option value="admin" @selected(request('role') === 'admin' || old('role') === 'admin')>Admin</option>
                            <option value="pimpinan" @selected(request('role') === 'pimpinan' || old('role') === 'pimpinan')>Pemimpin</option>
                        </select>
                    </div>
                </div>

                <!-- Input Identitas Akun sesuai Figma Node 12:11 & 12:15 -->
                <div class="mb-3">
                    <label for="login" class="form-label-figma">Email / NIM / NIP</label>
                    <input type="text" id="login" name="login"
                           value="{{ old('login') }}"
                           class="form-control form-control-login @error('login') is-invalid @enderror"
                           placeholder="Masukkan Email Atau NIM"
                           required autofocus autocomplete="username">
                </div>

                <!-- Input Kata Sandi sesuai Figma Node 12:19 & 12:21 -->
                <div class="mb-3">
                    <label for="password" class="form-label-figma">Kata Sandi</label>
                    <div class="password-field-wrapper">
                        <input type="password" id="password" name="password"
                               class="form-control form-control-login pe-5 @error('password') is-invalid @enderror"
                               placeholder="Masukkan Kata Sandi"
                               required autocomplete="current-password">
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility()" aria-label="Lihat Kata Sandi">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Ingat Saya & Lupa Kata Sandi sesuai Figma Node 14:31 & 14:33 -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label small text-muted" for="remember">
                            Ingat saya
                        </label>
                    </div>
                    <a href="javascript:void(0)" onclick="alert('Silakan hubungi administrator fakultas untuk me-reset kata sandi akun Anda.')"
                       class="small fw-semibold text-decoration-none" style="color: #4057a2;">
                        Lupa kata sandi?
                    </a>
                </div>

                <!-- Tombol Submit LOGIN sesuai Figma Node 14:35 & 14:37 -->
                <button type="submit" class="btn btn-figma-submit mb-3">
                    LOGIN
                </button>

                <!-- Pemisah Garis ATAU sesuai Figma Node 38:113 -->
                <div class="separator-atau-wrapper">
                    <div class="separator-atau-line"></div>
                    <span class="separator-atau-text">Atau</span>
                    <div class="separator-atau-line"></div>
                </div>

                <!-- Tombol SSO Alternatif sesuai Figma Node 38:128 & 38:136 -->
                <a href="javascript:void(0)" onclick="alert('Fitur login Google terintegrasi sedang dalam penyiapan SSO kampus.')" class="btn-sso-figma">
                    <i class="bi bi-google text-danger sso-icon-brand"></i>
                    <span>Login Dengan Google</span>
                </a>

                <a href="javascript:void(0)" onclick="alert('Fitur login Microsoft terintegrasi sedang dalam penyiapan SSO kampus.')" class="btn-sso-figma">
                    <i class="bi bi-microsoft text-primary sso-icon-brand"></i>
                    <span>Login Dengan Microsoft</span>
                </a>

                <!-- Footer Teks Catatan sesuai Figma Node 14:39 -->
                <div class="footer-note-link">
                    Belum Memiliki akun? <a href="{{ route('landing') }}#kontak">Hubungi Admin</a>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('pilih-peran') }}" class="small text-muted text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i> Ganti Pilihan Peran
                    </a>
                </div>
            </form>
        </div>

        <!-- Ornamen Lengkungan 3 Warna Sudut Kanan Bawah Sesuai Desain Figma (#100E0E, #535020, #4E0909) -->
        <div class="auth-corner-waves" aria-hidden="true">
            <svg viewBox="0 0 600 450" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <!-- 1. Lapisan Lengkung Atas (#100E0E) -->
                <path d="M 0 450 C 180 410, 380 290, 600 50 L 600 450 Z" fill="#100E0E" />
                <!-- 2. Lapisan Lengkung Tengah (#535020) -->
                <path d="M 160 450 C 300 410, 460 300, 600 160 L 600 450 Z" fill="#535020" />
                <!-- 3. Lapisan Lengkung Bawah (#4E0909) -->
                <path d="M 320 450 C 420 420, 520 330, 600 270 L 600 450 Z" fill="#4E0909" />
            </svg>
        </div>
    </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const pwdInput = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
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
