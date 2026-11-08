<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | SIMPRES - UIN Imam Bonjol Padang</title>
    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --simpres-olive: #5b6743;
            --simpres-olive-dark: #4a5435;
            --simpres-olive-light: #707e53;
            --simpres-maroon: #7a1c2c;
            --simpres-maroon-dark: #5e1421;
            --simpres-gold: #cfa83c;
            --simpres-bg: #f8f9fa;
            /* 5-Color Stop Figma Gradient (#54511A, #DED987, #66621F, #6A6621, #BAB33A) */
            --simpres-figma-gradient: linear-gradient(90deg, #54511a 0%, #ded987 5.77%, #66621f 17.31%, #6a6621 21.15%, #bab33a 100%);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--simpres-bg);
            color: #2b2d42;
            min-height: 100vh;
        }

        /* Top decorative accent line from Figma header */
        .top-accent-line {
            height: 3px;
            width: 100%;
            background: var(--simpres-figma-gradient);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 2000;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #5c6843 0%, #4f5939 80%, #3e462c 100%);
            color: #ffffff;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 16px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 24px 20px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        .sidebar-brand .brand-icon {
            width: 42px;
            height: 42px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .sidebar-user {
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        .sidebar-user .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #ffffff;
            color: var(--simpres-olive-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .sidebar-user .user-info {
            line-height: 1.25;
        }

        .sidebar-user .user-name {
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 2px;
        }

        .sidebar-user .user-role {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.85);
            text-transform: capitalize;
        }

        .sidebar-menu {
            padding: 16px 12px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .sidebar-menu .nav-link {
            color: rgba(255, 255, 255, 0.9);
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s;
        }

        .sidebar-menu .nav-link i {
            font-size: 18px;
            width: 22px;
            text-align: center;
        }

        .sidebar-menu .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.16);
            transform: translateX(3px);
        }

        .sidebar-menu .nav-link.active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.25);
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Sidebar footer with maroon wave accent */
        .sidebar-footer {
            position: relative;
            padding: 20px 16px;
            margin-top: auto;
            background: #5b6743;
            overflow: hidden;
        }

        .sidebar-footer::before {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: radial-gradient(circle at 100% 100%, #7a1c2c 0%, #5e1421 70%, transparent 70%);
            opacity: 0.85;
            pointer-events: none;
        }

        .sidebar-footer .btn-logout {
            position: relative;
            z-index: 2;
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            color: #ffffff;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
            cursor: pointer;
        }

        .sidebar-footer .btn-logout:hover {
            background: rgba(122, 28, 44, 0.85);
            border-color: #7a1c2c;
            color: #fff;
        }

        /* Main Content Layout */
        .main-wrapper {
            margin-left: 260px;
            padding: 24px 32px 60px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Top Navbar */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px;
        }

        .topbar-search {
            position: relative;
            max-width: 480px;
            width: 100%;
        }

        .topbar-search input {
            padding-left: 42px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            font-size: 14px;
            height: 44px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        }

        .topbar-search input:focus {
            border-color: var(--simpres-olive);
            box-shadow: 0 0 0 3px rgba(91, 103, 67, 0.15);
        }

        .topbar-search .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 16px;
        }

        /* Stat Card */
        .stat-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid #eef2f6;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }

        .stat-icon {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            flex-shrink: 0;
        }

        .stat-icon.olive {
            background: rgba(91, 103, 67, 0.12);
            color: var(--simpres-olive);
        }

        .stat-icon.success {
            background: rgba(40, 167, 69, 0.12);
            color: #28a745;
        }

        .stat-icon.warning {
            background: rgba(255, 193, 7, 0.15);
            color: #d39e00;
        }

        .stat-icon.danger {
            background: rgba(220, 53, 69, 0.12);
            color: #dc3545;
        }

        .stat-icon.info {
            background: rgba(13, 202, 240, 0.15);
            color: #0dcaf0;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 800;
            line-height: 1.1;
            color: #1e293b;
        }

        .stat-label {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
            margin-top: 2px;
        }

        /* Section Card */
        .content-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #eef2f6;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            margin-bottom: 24px;
        }

        .content-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .content-card-title {
            font-size: 17px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Buttons */
        .btn-olive {
            background-color: var(--simpres-olive);
            color: #ffffff;
            border: none;
            font-weight: 600;
            padding: 8px 18px;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .btn-olive:hover {
            background-color: var(--simpres-olive-dark);
            color: #ffffff;
        }

        /* Figma Theme Components */
        .btn-figma-olive {
            background-color: #535020;
            color: #ffffff;
            border: none;
            font-weight: 600;
            border-radius: 8px;
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-figma-olive:hover {
            background-color: #3e3b16;
            color: #ffffff;
        }

        .btn-figma-cancel {
            background-color: #dedee5;
            color: #100e0e;
            border: none;
            font-weight: 600;
            border-radius: 8px;
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-figma-cancel:hover {
            background-color: #cbd5e1;
            color: #100e0e;
        }

        .form-control-figma, .form-select-figma {
            background-color: #f1f2f6;
            border: 1px solid transparent;
            border-radius: 8px;
            min-height: 48px;
            font-size: 15px;
            color: #100e0e;
            transition: all 0.2s ease;
        }

        .form-control-figma:focus, .form-select-figma:focus {
            background-color: #ffffff;
            border-color: #535020;
            box-shadow: 0 0 0 0.2rem rgba(83, 80, 32, 0.15);
        }

        .dropzone-figma {
            background-color: #f2f2f7;
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            transition: all 0.2s ease;
        }

        .dropzone-figma:hover {
            border-color: #535020;
            background-color: #ebedf2;
        }

        .btn-maroon {
            background-color: var(--simpres-maroon);
            color: #ffffff;
            border: none;
            font-weight: 600;
            padding: 8px 18px;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .btn-maroon:hover {
            background-color: var(--simpres-maroon-dark);
            color: #ffffff;
        }

        /* Logout Modal */
        .modal-logout .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .modal-logout .modal-header {
            background: #f8fafc;
            border-bottom: 1px solid #eef2f6;
        }

        .modal-logout .exit-icon-wrap {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(122, 28, 44, 0.1);
            color: var(--simpres-maroon);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 16px;
        }

        /* Mobile Responsive */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
                padding: 16px;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="top-accent-line"></div>
    @auth
        <!-- Left Sidebar Navigation -->
        <aside class="sidebar" id="sidebarNav">
            <a href="{{ route('dashboard') }}" class="sidebar-brand text-decoration-none">
                <div class="brand-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-white">SIMPRES</h6>
                    <small class="text-white-50" style="font-size: 11px;">FST UIN Imam Bonjol</small>
                </div>
            </a>

            <div class="sidebar-user">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->username, 0, 1)) }}
                </div>
                <div class="user-info">
                    <div class="user-name text-truncate" style="max-width: 160px;">{{ auth()->user()->nama ?? auth()->user()->username }}</div>
                    <div class="user-role">
                        @if(auth()->user()->isAdmin())
                            <span class="badge bg-white text-dark py-1 px-2" style="font-size: 10px;">Admin</span>
                        @elseif(auth()->user()->isPimpinan())
                            <span class="badge bg-warning text-dark py-1 px-2" style="font-size: 10px;">Pemimpin</span>
                        @else
                            <span class="badge bg-light text-dark py-1 px-2" style="font-size: 10px;">Mahasiswa</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="sidebar-menu">
                @if(auth()->user()->isMahasiswa())
                    <!-- Menu Mahasiswa (Lap. hal. 103-104) -->
                    <a href="{{ route('mahasiswa.dashboard') }}" class="nav-link @if(request()->routeIs('mahasiswa.dashboard')) active @endif">
                        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                    </a>
                    <a href="{{ route('profil.show') }}" class="nav-link @if(request()->routeIs('profil.*')) active @endif">
                        <i class="bi bi-person-circle"></i> <span>Profil</span>
                    </a>
                    <a href="{{ route('mahasiswa.prestasi.create') }}" class="nav-link @if(request()->routeIs('mahasiswa.prestasi.create')) active @endif" id="sidebar-nav-data">
                        <i class="bi bi-plus-circle"></i> <span>Data Prestasi</span>
                    </a>
                    <a href="{{ route('mahasiswa.upload.sertifikat') }}" class="nav-link @if(request()->routeIs('mahasiswa.upload.sertifikat*')) active @endif" id="sidebar-nav-sertifikat">
                        <i class="bi bi-cloud-arrow-up"></i> <span>Upload Sertifikat</span>
                    </a>
                    <a href="{{ route('mahasiswa.upload.dokumentasi') }}" class="nav-link @if(request()->routeIs('mahasiswa.upload.dokumentasi*')) active @endif" id="sidebar-nav-dokumentasi">
                        <i class="bi bi-images"></i> <span>Upload Dokumentasi</span>
                    </a>
                    <a href="{{ route('mahasiswa.prestasi.index') }}" class="nav-link @if(request()->routeIs('mahasiswa.prestasi.index', 'mahasiswa.prestasi.show', 'mahasiswa.prestasi.edit')) active @endif">
                        <i class="bi bi-clock-history"></i> <span>Riwayat Prestasi</span>
                    </a>
                    <a href="{{ route('mahasiswa.notifikasi.index') }}" class="nav-link @if(request()->routeIs('mahasiswa.notifikasi.*')) active @endif">
                        <i class="bi bi-bell"></i> <span>Notifikasi</span>
                        @if(($jumlahNotifikasi ?? 0) > 0)
                            <span class="badge bg-danger ms-auto rounded-pill">{{ $jumlahNotifikasi }}</span>
                        @endif
                    </a>

                @elseif(auth()->user()->isAdmin())
                    <!-- Menu Admin (Lap. hal. 115-116) -->
                    <a href="{{ route('admin.dashboard') }}" class="nav-link @if(request()->routeIs('admin.dashboard')) active @endif">
                        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                    </a>
                    <a href="{{ route('profil.show') }}" class="nav-link @if(request()->routeIs('profil.*')) active @endif">
                        <i class="bi bi-person-circle"></i> <span>Profil</span>
                    </a>
                    <a href="{{ route('admin.verifikasi.index') }}" class="nav-link @if(request()->routeIs('admin.verifikasi.*')) active @endif">
                        <i class="bi bi-check2-square"></i> <span>Data Prestasi</span>
                    </a>
                    <a href="{{ route('admin.rekapitulasi') }}" class="nav-link @if(request()->routeIs('admin.rekapitulasi')) active @endif">
                        <i class="bi bi-pie-chart"></i> <span>Rekapitulasi</span>
                    </a>
                    <a href="{{ route('admin.riwayat') }}" class="nav-link @if(request()->routeIs('admin.riwayat')) active @endif">
                        <i class="bi bi-journal-text"></i> <span>Riwayat Data</span>
                    </a>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="nav-link @if(request()->routeIs('admin.mahasiswa.*')) active @endif">
                        <i class="bi bi-people"></i> <span>Data Mahasiswa</span>
                    </a>

                @elseif(auth()->user()->isPimpinan())
                    <!-- Menu Pimpinan (Lap. hal. 127-128) -->
                    <a href="{{ route('pimpinan.dashboard') }}" class="nav-link @if(request()->routeIs('pimpinan.dashboard')) active @endif">
                        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                    </a>
                    <a href="{{ route('pimpinan.rekapitulasi') }}" class="nav-link @if(request()->routeIs('pimpinan.rekapitulasi')) active @endif">
                        <i class="bi bi-pie-chart"></i> <span>Rekapitulasi</span>
                    </a>
                    <a href="{{ route('pimpinan.prestasi.index') }}" class="nav-link @if(request()->routeIs('pimpinan.prestasi.*')) active @endif">
                        <i class="bi bi-file-earmark-bar-graph"></i> <span>Data Prestasi</span>
                    </a>
                    <a href="{{ route('profil.show') }}" class="nav-link @if(request()->routeIs('profil.*')) active @endif">
                        <i class="bi bi-person-circle"></i> <span>Profil</span>
                    </a>
                @endif
            </div>

            <!-- Footer Sidebar dengan Tombol Logout (Memicu Modal Konfirmasi) -->
            <div class="sidebar-footer">
                <button type="button" class="btn-logout" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                </button>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="main-wrapper">
            <!-- Topbar Header -->
            <div class="topbar">
                <button class="btn btn-outline-secondary d-lg-none" type="button" onclick="document.getElementById('sidebarNav').classList.toggle('show')">
                    <i class="bi bi-list fs-5"></i>
                </button>

                <div class="topbar-search d-none d-sm-block">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" class="form-control" placeholder="Cari data prestasi, nama, atau kegiatan..." id="globalSearchInput" onkeydown="if(event.key==='Enter'){ const url = new URL(window.location.href); url.searchParams.set('q', this.value); window.location.href = url.toString(); }">
                </div>

                <div class="d-flex align-items-center gap-3 ms-auto">
                    <a href="{{ route('landing') }}" class="btn btn-sm btn-outline-secondary d-none d-md-inline-flex align-items-center gap-1 rounded-pill px-3" title="Lihat Halaman Beranda Publik">
                        <i class="bi bi-house-door"></i>
                        <span>Beranda Publik</span>
                    </a>

                    @if(auth()->user()->isMahasiswa() && ($jumlahNotifikasi ?? 0) > 0)
                        <a href="{{ route('mahasiswa.notifikasi.index') }}" class="position-relative text-dark text-decoration-none">
                            <i class="bi bi-bell-fill fs-5 text-secondary"></i>
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                        </a>
                    @endif

                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold small d-none d-md-inline">{{ auth()->user()->nama ?? auth()->user()->username }}</span>
                        <a href="{{ route('profil.show') }}" class="text-decoration-none">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 36px; height: 36px; background-color: var(--simpres-olive);">
                                {{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->username, 0, 1)) }}
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Flash Message Alerts -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                        <strong class="text-danger">Terdapat kesalahan:</strong>
                    </div>
                    <ul class="mb-0 ps-4 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </div>

        <!-- Halaman Konfirmasi Logout Modal (Lap. hal. 113-114, 125-126, 131-132) -->
        <div class="modal fade modal-logout" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content text-center p-4">
                    <div class="modal-body p-2">
                        <div class="exit-icon-wrap">
                            <i class="bi bi-box-arrow-right"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Keluar dari Sistem ?</h5>
                        <p class="text-muted small mb-4">Apakah Anda yakin keluar dari sistem?</p>

                        <div class="d-flex justify-content-center gap-3">
                            <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-3 fw-semibold" data-bs-dismiss="modal">Tidak</button>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                                @csrf
                                <button type="submit" class="btn btn-danger px-4 py-2 rounded-3 fw-semibold" style="background-color: var(--simpres-maroon); border-color: var(--simpres-maroon);">IYA</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Guest View (Login / Landing) -->
        @yield('content')
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
