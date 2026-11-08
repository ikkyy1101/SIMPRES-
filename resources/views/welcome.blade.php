<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMPRES - Sistem Informasi Manajemen Prestasi Mahasiswa Fakultas Sains dan Teknologi UIN Imam Bonjol Padang</title>
    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hammersmith+One&family=Playfair+Display:ital,wght@0,600;0,700;1,600;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --simpres-olive-dark: #3e3b16;
            --simpres-olive: #54511a;
            --simpres-olive-brand: #535020;
            --simpres-olive-light: #736f2f;
            --simpres-neutral-card: #dedee5;
            --simpres-maroon: #7a1c2c;
            --simpres-gold: #d4a373;
            /* 5-Color Stop Figma Gradient (#54511A, #DED987, #66621F, #6A6621, #BAB33A) */
            --simpres-figma-gradient: linear-gradient(90deg, #54511a 0%, #ded987 5.77%, #66621f 17.31%, #6a6621 21.15%, #bab33a 100%);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #ffffff;
            color: #100e0e;
            overflow-x: hidden;
        }

        /* Navbar Header sesuai Figma Node 125:212 dengan gradasi 5 warna */
        .landing-navbar {
            background: var(--simpres-figma-gradient);
            min-height: 96px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            padding: 10px 0;
        }

        .landing-navbar .navbar-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            color: #ffffff;
            text-decoration: none;
        }

        .landing-navbar .brand-logo-img {
            width: 58px;
            height: 58px;
            object-fit: contain;
            border-radius: 12px;
            background: #ffffff;
            padding: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .landing-navbar .brand-title {
            font-size: 30px;
            font-weight: 800;
            letter-spacing: 0.5px;
            line-height: 1.1;
            color: #ffffff;
        }

        .landing-navbar .brand-subtitle {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            letter-spacing: 0.2px;
        }

        .landing-navbar .nav-link {
            color: #ffffff;
            font-size: 18px;
            font-weight: 500;
            margin: 0 16px;
            padding: 8px 4px;
            position: relative;
            transition: all 0.2s ease;
        }

        .landing-navbar .nav-link:hover {
            color: #fef08a;
        }

        .landing-navbar .nav-link.active::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 3px;
            background-color: #ffffff;
            border-radius: 2px;
        }

        .btn-figma-login {
            background-color: #54511a;
            color: #ffffff;
            border: 1.5px solid rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            padding: 10px 32px;
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            transition: all 0.2s ease;
        }

        .btn-figma-login:hover {
            background-color: #3e3b16;
            color: #ffffff;
            border-color: #ffffff;
            transform: translateY(-2px);
        }

        /* Hero Section sesuai Figma Node 125:212 */
        .hero-section {
            padding: 60px 0 50px;
            position: relative;
            background-color: #ffffff;
            overflow: hidden;
        }

        .hero-title-main {
            font-size: 46px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 18px;
            letter-spacing: -0.5px;
        }

        .hero-title-olive {
            color: #54511a;
        }

        .hero-title-dark {
            color: #100e0e;
        }

        .hero-subheading-fst {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-style: italic;
            font-weight: 600;
            color: #54511a;
            line-height: 1.25;
            margin-bottom: 4px;
        }

        .hero-subheading-uin {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-style: italic;
            font-weight: 700;
            color: #100e0e;
            line-height: 1.25;
            margin-bottom: 28px;
        }

        /* Slogan Banner sesuai Figma Node 36:98 */
        .slogan-card-figma {
            background-color: #54511a;
            color: #ffffff;
            border-radius: 15px;
            padding: 24px 32px;
            margin-bottom: 34px;
            box-shadow: 0 10px 24px rgba(84, 81, 26, 0.22);
            max-width: 540px;
        }

        .slogan-text-figma {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-style: italic;
            font-weight: 600;
            line-height: 1.4;
            margin: 0;
            color: #ffffff;
        }

        /* Feature Cards sesuai Figma Node 32:43, 32:46, 32:53 */
        .feature-card-figma {
            background-color: #dedee5;
            border-radius: 15px;
            padding: 14px 18px;
            min-height: 76px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: all 0.2s ease;
            text-decoration: none;
            color: inherit;
        }

        .feature-card-figma:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        }

        .feature-icon-img {
            width: 38px;
            height: 38px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .feature-title-figma {
            font-family: 'Hammersmith One', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: #535020;
            margin-bottom: 2px;
            line-height: 1.2;
        }

        .feature-desc-figma {
            font-size: 12px;
            color: #100e0e;
            margin-bottom: 0;
            line-height: 1.25;
        }

        /* Hero Image Frame sesuai Figma Node 32:11 */
        .hero-ellipse-wrapper {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-ellipse-img {
            width: 100%;
            max-width: 580px;
            height: auto;
            object-fit: cover;
            border-radius: 40px;
            filter: drop-shadow(0 16px 36px rgba(84, 81, 26, 0.16));
        }

        /* Stat Counter Section */
        .stat-counter-section {
            background: linear-gradient(135deg, #54511a 0%, #3e3b16 100%);
            color: #ffffff;
            padding: 38px 0;
            margin-top: 40px;
            border-radius: 20px;
            box-shadow: 0 12px 30px rgba(84, 81, 26, 0.2);
        }

        .stat-counter-item {
            text-align: center;
            padding: 8px 16px;
        }

        .stat-counter-number {
            font-size: 38px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.1;
            margin-bottom: 6px;
        }

        .stat-counter-label {
            font-size: 14px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.85);
        }

        /* Prestasi Card */
        .prestasi-card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #ffffff;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .prestasi-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(84, 81, 26, 0.12);
            border-color: #54511a;
        }

        /* Section Headers */
        .section-header {
            text-align: center;
            margin-bottom: 48px;
        }

        .section-tag {
            color: #54511a;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .section-title {
            font-size: 32px;
            font-weight: 800;
            color: #100e0e;
        }

        /* Footer */
        .landing-footer {
            background-color: #54511a;
            color: #ffffff;
            padding: 44px 0 24px;
            border-top: 4px solid #686422;
        }

        @media (max-width: 991.98px) {
            .hero-title-main {
                font-size: 34px;
            }
            .hero-subheading-fst {
                font-size: 20px;
            }
            .hero-subheading-uin {
                font-size: 22px;
            }
            .slogan-text-figma {
                font-size: 18px;
            }
            .landing-navbar .nav-link {
                margin: 6px 0;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar Header sesuai Figma Node 125:212 -->
    <nav class="navbar navbar-expand-lg landing-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/landing/logo-simpres.png') }}" alt="Logo SIMPRES" class="brand-logo-img">
                <div>
                    <div class="brand-title">SIMPRES</div>
                    <div class="brand-subtitle">Fakultas Sains Dan Teknologi</div>
                </div>
            </a>

            <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navContent" aria-label="Toggle navigation">
                <i class="bi bi-list fs-2"></i>
            </button>

            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav mx-auto my-2 my-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#prestasi">Prestasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Kontak</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    @auth
                        @php
                            $user = auth()->user();
                            $dashboardUrl = route('mahasiswa.dashboard');
                            if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
                                $dashboardUrl = route('admin.dashboard');
                            } elseif (method_exists($user, 'isPimpinan') && $user->isPimpinan()) {
                                $dashboardUrl = route('pimpinan.dashboard');
                            }
                        @endphp
                        <a href="{{ $dashboardUrl }}" class="btn-figma-login">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-light rounded-2 px-3 py-2" title="Keluar">
                                <i class="bi bi-box-arrow-right"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('pilih-peran') }}" class="btn-figma-login">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section sesuai Figma Node 125:212 -->
    <section class="hero-section" id="beranda">
        <div class="container">
            <div class="row align-items-center g-5">
                <!-- Kolom Kiri: Teks Judul, Slogan, dan 3 Kartu Keunggulan -->
                <div class="col-lg-6">
                    <h1 class="hero-title-main">
                        <span class="hero-title-olive">Sistem </span><span class="hero-title-dark">Informasi</span><br>
                        <span class="hero-title-olive">Manajemen </span><span class="hero-title-dark">Prestasi</span><br>
                        <span class="hero-title-olive">Mahasiswa</span>
                    </h1>

                    <div class="hero-subheading-fst">Fakultas Sains Dan Teknologi</div>
                    <div class="hero-subheading-uin">UIN Imam Bonjol Padang</div>

                    <!-- Slogan Card sesuai Figma Node 36:98 -->
                    <div class="slogan-card-figma">
                        <p class="slogan-text-figma">
                            “Mencatat Prestasi,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mengukir Masa Depan”
                        </p>
                    </div>

                    <!-- Tombol Aksi Cepat -->
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        @auth
                            <a href="{{ $dashboardUrl }}" class="btn btn-figma-login px-4 py-3 fw-bold shadow-sm" style="background-color: #54511a; border-radius: 10px;">
                                Buka Dashboard Saya <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        @else
                            <a href="{{ route('pilih-peran') }}" class="btn btn-figma-login px-4 py-3 fw-bold shadow-sm" style="background-color: #54511a; border-radius: 10px;">
                                Masuk ke Sistem <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        @endauth
                        <a href="#tentang" class="btn btn-outline-secondary px-4 py-3 fw-semibold rounded-3 d-inline-flex align-items-center">
                            Pelajari Selengkapnya
                        </a>
                    </div>

                    <!-- 3 Kartu Keunggulan sesuai Figma (Node 32:43, 32:46, 32:53) -->
                    <div class="row g-3">
                        <div class="col-12 col-sm-4">
                            <div class="feature-card-figma">
                                <img src="{{ asset('images/landing/icon-easy.png') }}" alt="Ikon Mudah Digunakan" class="feature-icon-img">
                                <div>
                                    <div class="feature-title-figma">Mudah digunakan</div>
                                    <div class="feature-desc-figma">Akses cepat dan praktis</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-4">
                            <div class="feature-card-figma">
                                <img src="{{ asset('images/landing/icon-security.png') }}" alt="Ikon Data Aman" class="feature-icon-img">
                                <div>
                                    <div class="feature-title-figma">Data Aman</div>
                                    <div class="feature-desc-figma">keamanan data terjamin</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-4">
                            <div class="feature-card-figma">
                                <img src="{{ asset('images/landing/icon-circuit.png') }}" alt="Ikon Terintegrasi" class="feature-icon-img">
                                <div>
                                    <div class="feature-title-figma">Terintegrasi</div>
                                    <div class="feature-desc-figma">terhubung dengan fakultas dan admin</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Foto Ilustrasi Ellipse Mahasiswa sesuai Figma Node 32:11 -->
                <div class="col-lg-6">
                    <div class="hero-ellipse-wrapper">
                        <img src="{{ asset('images/landing/hero-ellipse.png') }}" alt="Prestasi Mahasiswa FST UIN Imam Bonjol Padang" class="hero-ellipse-img shadow-sm">
                    </div>
                </div>
            </div>

            <!-- Dynamic Live Stats Counter -->
            <div class="stat-counter-section">
                <div class="row g-4 align-items-center">
                    <div class="col-6 col-md-3">
                        <div class="stat-counter-item">
                            <div class="stat-counter-number">{{ $totalPrestasi ?? 0 }}</div>
                            <div class="stat-counter-label">Prestasi Terverifikasi</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-counter-item">
                            <div class="stat-counter-number">{{ $mahasiswaBerprestasi ?? 0 }}</div>
                            <div class="stat-counter-label">Mahasiswa Berprestasi</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-counter-item">
                            <div class="stat-counter-number">{{ $kategoriCount ?? 6 }}</div>
                            <div class="stat-counter-label">Kategori Bidang</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-counter-item">
                            <div class="stat-counter-number">100%</div>
                            <div class="stat-counter-label">Terdokumentasi Resmi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Prestasi Terkini Mahasiswa FST Section -->
    <section class="py-5" id="prestasi" style="background-color: #ffffff;">
        <div class="container py-4">
            <div class="section-header">
                <div class="section-tag">Capaian & Prestasi</div>
                <h2 class="section-title">Prestasi Mahasiswa Terkini</h2>
                <p class="text-muted mx-auto" style="max-width: 650px;">
                    Kebanggaan civitas akademika Fakultas Sains dan Teknologi UIN Imam Bonjol Padang dalam berbagai ajang kompetisi tingkat lokal, nasional, hingga internasional.
                </p>
            </div>

            @if(isset($prestasiTerbaru) && $prestasiTerbaru->isNotEmpty())
                <div class="row g-4">
                    @foreach ($prestasiTerbaru as $prestasi)
                        <div class="col-md-6 col-lg-4">
                            <div class="prestasi-card h-100 p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                                        <span class="badge rounded-pill px-3 py-2" style="background-color: rgba(84, 81, 26, 0.12); color: #54511a; font-size: 11px;">
                                            {{ $prestasi->jenisLabel() }}
                                        </span>
                                        <span class="badge rounded-pill px-3 py-2 bg-warning text-dark fw-semibold" style="font-size: 11px;">
                                            <i class="bi bi-trophy-fill me-1"></i> {{ $prestasi->perolehanLabel() }}
                                        </span>
                                    </div>
                                    <h5 class="fw-bold mb-2 text-dark" style="font-size: 16px; line-height: 1.4;">
                                        {{ $prestasi->nama_prestasi }}
                                    </h5>
                                    <p class="text-muted small mb-3">
                                        <i class="bi bi-building me-1"></i> {{ $prestasi->penyelenggara }}
                                    </p>
                                </div>
                                <div class="pt-3 border-top mt-3">
                                    <div class="d-flex align-items-center justify-content-between small">
                                        <div>
                                            <strong class="text-dark d-block">{{ $prestasi->mahasiswa->nama ?? 'Mahasiswa FST' }}</strong>
                                            <span class="text-muted" style="font-size: 11px;">{{ $prestasi->mahasiswa->prodi ?? 'Sains & Teknologi' }}</span>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-light text-dark border">{{ $prestasi->tingkatLabel() }}</span>
                                            <div class="text-muted mt-1" style="font-size: 11px;">Tahun {{ $prestasi->tahun_perolehan }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card border-0 rounded-4 shadow-sm p-5 text-center bg-light">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 72px; height: 72px; background: rgba(84, 81, 26, 0.12); color: #54511a;">
                        <i class="bi bi-trophy fs-2"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Pencatatan Prestasi Mahasiswa FST</h5>
                    <p class="text-muted mx-auto mb-4" style="max-width: 500px;">
                        Setiap prestasi yang diajukan oleh mahasiswa akan diverifikasi oleh Admin Fakultas dan ditampilkan secara transparan di sistem.
                    </p>
                    <div>
                        <a href="{{ route('pilih-peran') }}" class="btn text-white px-4 py-2 rounded-3 shadow-sm" style="background-color: #54511a;">
                            Mulai Ajukan Prestasi
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Tentang Section -->
    <section class="py-5" id="tentang" style="background-color: #f8fafc;">
        <div class="container py-4">
            <div class="section-header">
                <div class="section-tag">Tentang Sistem</div>
                <h2 class="section-title">Solusi Digitalisasi Pendataan Prestasi</h2>
                <p class="text-muted mx-auto" style="max-width: 650px;">
                    Sistem Informasi Manajemen Prestasi Mahasiswa (SIMPRES) FST UIN Imam Bonjol Padang dirancang untuk mempermudah pencatatan, verifikasi, rekapitulasi, dan pemantauan prestasi akademik maupun non-akademik secara terpadu.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 rounded-4 shadow-sm p-4 text-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px; background: rgba(84, 81, 26, 0.12); color: #54511a;">
                            <i class="bi bi-mortarboard fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Bagi Mahasiswa</h5>
                        <p class="text-muted small">
                            Kemudahan dalam mendata prestasi secara terstruktur, mengunggah bukti sertifikat dan foto dokumentasi, serta memantau status verifikasi dan skor prestasi secara transparan.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 rounded-4 shadow-sm p-4 text-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px; background: rgba(122, 28, 44, 0.12); color: var(--simpres-maroon);">
                            <i class="bi bi-shield-check fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Bagi Admin</h5>
                        <p class="text-muted small">
                            Memeriksa kelengkapan dokumen pendukung, menentukan status verifikasi, memberikan skor prestasi sesuai ketentuan fakultas, serta memberikan catatan perbaikan secara interaktif.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 rounded-4 shadow-sm p-4 text-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px; background: rgba(212, 163, 115, 0.2); color: #9c6c39;">
                            <i class="bi bi-graph-up-arrow fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Bagi Pimpinan</h5>
                        <p class="text-muted small">
                            Dashboard analitik statistik dan rekapitulasi data prestasi secara visual untuk mendukung evaluasi, pencarian kandidat beasiswa, serta pemantauan kemajuan fakultas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak Section -->
    <section class="py-5 bg-white" id="kontak">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-md-6">
                    <div class="section-tag">Kontak & Informasi</div>
                    <h2 class="section-title mb-3">Fakultas Sains dan Teknologi</h2>
                    <p class="text-muted mb-4">
                        Universitas Islam Negeri (UIN) Imam Bonjol Padang<br>
                        Kampus III Sungai Bangek, Lubuk Kilangan, Kota Padang, Sumatera Barat.
                    </p>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-envelope-fill text-success fs-5"></i>
                            <span>fst@uinib.ac.id</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-telephone-fill text-success fs-5"></i>
                            <span>(0751) 9876543</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-globe text-success fs-5"></i>
                            <span>https://fst.uinib.ac.id</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 rounded-4 p-4 shadow-sm" style="background: var(--simpres-figma-gradient); color: #fff;">
                        <h4 class="fw-bold mb-2">Siap Mencatat Prestasi Anda?</h4>
                        <p class="text-white-50 mb-4">
                            Masuk menggunakan akun SIMPRES Anda sesuai peran (Mahasiswa, Admin, atau Pemimpin Fakultas).
                        </p>
                        @auth
                            <a href="{{ $dashboardUrl }}" class="btn btn-light fw-bold py-3 px-4 rounded-3 text-dark">
                                Menuju Dashboard <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        @else
                            <a href="{{ route('pilih-peran') }}" class="btn btn-light fw-bold py-3 px-4 rounded-3 text-dark">
                                Pilih Peran & Masuk <i class="bi bi-box-arrow-in-right ms-2"></i>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="landing-footer">
        <div class="container text-center">
            <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                <img src="{{ asset('images/landing/logo-simpres.png') }}" alt="Logo" style="width: 32px; height: 32px; object-fit: contain; background: #fff; border-radius: 6px; padding: 2px;">
                <span class="fw-bold fs-5">SIMPRES &bull; FST UIN Imam Bonjol Padang</span>
            </div>
            <p class="text-white-50 small mb-0">
                &copy; {{ date('Y') }} Sistem Informasi Manajemen Prestasi Mahasiswa Berbasis Web. All Rights Reserved.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
