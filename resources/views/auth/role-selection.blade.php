<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pilih Peran Anda | SIMPRES - UIN Imam Bonjol Padang</title>
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
            --simpres-neutral-light: #f1f2f6;
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

        /* Sisi Kiri: Banner Hero Kampus sesuai Figma Node 317:1144 */
        .auth-hero-pane {
            width: 52%;
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

        /* Sisi Kanan: Pilihan Peran sesuai Figma Node 317:1184 */
        .auth-role-pane {
            width: 48%;
            padding: 64px 72px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .role-header-title {
            font-size: 34px;
            font-weight: 800;
            color: #100e0e;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .role-header-subtitle {
            font-size: 15px;
            color: #64748b;
            margin-bottom: 36px;
        }

        /* Kartu Peran sesuai Figma Rectangle 4, 5, 6 (#dedee5) */
        .role-card-figma {
            background-color: #dedee5;
            border-radius: 16px;
            padding: 22px 28px;
            margin-bottom: 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 120px;
            border: 2px solid transparent;
            transition: all 0.25s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
        }

        .role-card-figma:hover {
            background-color: #ffffff;
            border-color: #535020;
            transform: translateY(-4px);
            box-shadow: 0 14px 28px rgba(83, 80, 32, 0.16);
        }

        .role-card-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .role-avatar-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            color: #535020;
            font-size: 28px;
            flex-shrink: 0;
            transition: all 0.25s ease;
        }

        .role-card-figma:hover .role-avatar-circle {
            background-color: #535020;
            color: #ffffff;
            transform: scale(1.05);
        }

        .role-name-text {
            font-size: 26px;
            font-weight: 800;
            color: #100e0e;
            line-height: 1.2;
            margin-bottom: 4px;
        }

        .role-desc-text {
            font-size: 13px;
            color: #64748b;
            margin: 0;
        }

        .role-action-arrow {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #535020;
            font-size: 20px;
            transition: transform 0.25s ease;
        }

        .role-card-figma:hover .role-action-arrow {
            transform: translateX(6px);
        }

        .back-link-home {
            color: #54511a;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 16px;
            font-size: 14px;
            transition: color 0.2s ease;
        }

        .back-link-home:hover {
            color: #3e3b16;
            text-decoration: underline;
        }

        /* Responsif Tablet & Mobile */
        @media (max-width: 991.98px) {
            .auth-split-wrapper {
                flex-direction: column;
            }
            .auth-hero-pane {
                width: 100%;
                padding: 40px 24px;
                min-height: 380px;
            }
            .auth-role-pane {
                width: 100%;
                padding: 40px 24px;
            }
            .hero-title-first, .hero-title-second {
                font-size: 28px;
            }
            .role-name-text {
                font-size: 22px;
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
                    Selamat datang di portal SIMPRES. Platform pendataan terpadu untuk mendokumentasikan, memverifikasi, dan mengarsipkan capaian kejuaraan civitas akademika Fakultas Sains dan Teknologi UIN Imam Bonjol Padang.
                </p>
            </div>

            <div class="auth-hero-footer">
                &copy; {{ date('Y') }} FST UIN Imam Bonjol Padang &bull; SIMPRES Berbasis Web
            </div>
        </div>

        <!-- Kolom Kanan: Pilihan Peran Pengguna sesuai Figma 317:1184 -->
        <div class="auth-role-pane">
            <h1 class="role-header-title">Pilih Peran Anda</h1>
            <p class="role-header-subtitle">Silakan pilih peran pengguna untuk melanjutkan proses masuk ke sistem:</p>

            <!-- Kartu Peran 1: Mahasiswa -->
            <div class="role-card-figma position-relative">
                <a href="{{ route('login', ['role' => 'mahasiswa']) }}" class="text-decoration-none text-dark flex-grow-1 d-flex align-items-center">
                    <div class="role-card-info">
                        <div class="role-avatar-circle">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <div>
                            <div class="role-name-text">Mahasiswa</div>
                            <p class="role-desc-text">Pengajuan prestasi, unggah bukti sertifikat, dan pantau verifikasi</p>
                        </div>
                    </div>
                </a>
                <div class="d-flex align-items-center gap-2 ps-3">
                    <form action="{{ route('login.quick', ['role' => 'mahasiswa']) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm text-white px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1"
                                style="background-color: #535020; font-size: 12px; font-weight: 600;" title="Login langsung sebagai Mahasiswa (Alya putri)">
                            <i class="bi bi-lightning-charge-fill text-warning"></i>
                            <span>Login Cepat</span>
                        </button>
                    </form>
                    <a href="{{ route('login', ['role' => 'mahasiswa']) }}" class="role-action-arrow">
                        <i class="bi bi-arrow-right-circle-fill fs-3"></i>
                    </a>
                </div>
            </div>

            <!-- Kartu Peran 2: Admin -->
            <div class="role-card-figma position-relative">
                <a href="{{ route('login', ['role' => 'admin']) }}" class="text-decoration-none text-dark flex-grow-1 d-flex align-items-center">
                    <div class="role-card-info">
                        <div class="role-avatar-circle">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <div>
                            <div class="role-name-text">Admin</div>
                            <p class="role-desc-text">Verifikasi dokumen, penilaian skor, dan kelola data mahasiswa</p>
                        </div>
                    </div>
                </a>
                <div class="d-flex align-items-center gap-2 ps-3">
                    <form action="{{ route('login.quick', ['role' => 'admin']) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm text-white px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1"
                                style="background-color: #535020; font-size: 12px; font-weight: 600;" title="Login langsung sebagai Admin (Aura Maylia)">
                            <i class="bi bi-lightning-charge-fill text-warning"></i>
                            <span>Login Cepat</span>
                        </button>
                    </form>
                    <a href="{{ route('login', ['role' => 'admin']) }}" class="role-action-arrow">
                        <i class="bi bi-arrow-right-circle-fill fs-3"></i>
                    </a>
                </div>
            </div>

            <!-- Kartu Peran 3: Pemimpin -->
            <div class="role-card-figma position-relative">
                <a href="{{ route('login', ['role' => 'pimpinan']) }}" class="text-decoration-none text-dark flex-grow-1 d-flex align-items-center">
                    <div class="role-card-info">
                        <div class="role-avatar-circle">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div>
                            <div class="role-name-text">Pemimpin</div>
                            <p class="role-desc-text">Akses visual analitik capaian prestasi dan laporan rekapitulasi</p>
                        </div>
                    </div>
                </a>
                <div class="d-flex align-items-center gap-2 ps-3">
                    <form action="{{ route('login.quick', ['role' => 'pimpinan']) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm text-white px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1"
                                style="background-color: #535020; font-size: 12px; font-weight: 600;" title="Login langsung sebagai Pemimpin (Selvia P.)">
                            <i class="bi bi-lightning-charge-fill text-warning"></i>
                            <span>Login Cepat</span>
                        </button>
                    </form>
                    <a href="{{ route('login', ['role' => 'pimpinan']) }}" class="role-action-arrow">
                        <i class="bi bi-arrow-right-circle-fill fs-3"></i>
                    </a>
                </div>
            </div>

            <div>
                <a href="{{ route('landing') }}" class="back-link-home">
                    <i class="bi bi-arrow-left"></i> Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>
</body>

</html>
