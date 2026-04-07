<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SI KUDA KEPANG') - Sistem Informasi Kumpulan Data Kecamatan Pangalengan</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --stunting-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --kemiskinan-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --lingkungan-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --sidebar-width: 280px;
            --header-height: 70px;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0d4a2e 0%, #072c1a 100%);
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1a5f3c 0%, #0d2a1b 100%);
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand {
            color: white;
            font-weight: 800;
            font-size: 1.5rem;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 0.5rem;
        }

        .sidebar-brand img {
            width: 85px;
            height: 85px;
            object-fit: contain;
        }

        .sidebar-brand small {
            display: block;
            font-size: 0.6rem;
            font-weight: 400;
            opacity: 0.8;
            margin-top: 2px;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .menu-label {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0.75rem 1.5rem 0.5rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            gap: 0.75rem;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            border-left-color: rgba(255, 255, 255, 0.3);
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: #667eea;
        }

        .menu-item i {
            font-size: 1.2rem;
            width: 24px;
        }

        /* Portal Sections */
        .portal-section {
            margin-top: 0.5rem;
        }

        .portal-header {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            gap: 0.75rem;
            cursor: pointer;
        }

        .portal-header.stunting {
            background: linear-gradient(90deg, rgba(240, 147, 251, 0.2), transparent);
        }

        .portal-header.kemiskinan {
            background: linear-gradient(90deg, rgba(79, 172, 254, 0.2), transparent);
        }

        .portal-header.lingkungan {
            background: linear-gradient(90deg, rgba(67, 233, 123, 0.2), transparent);
        }

        .portal-menu .menu-item {
            padding-left: 3rem;
            font-size: 0.85rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .header {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        }

        .page-title {
            font-weight: 700;
            font-size: 1.25rem;
            color: #1e3a5f;
            margin: 0;
        }

        .content-wrapper {
            padding: 2rem;
        }

        /* Cards */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: none;
            overflow: hidden;
            position: relative;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .stat-card.stunting::before {
            background: var(--stunting-gradient);
        }

        .stat-card.kemiskinan::before {
            background: var(--kemiskinan-gradient);
        }

        .stat-card.lingkungan::before {
            background: var(--lingkungan-gradient);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .stat-card .icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-card.stunting .icon-wrapper {
            background: var(--stunting-gradient);
        }

        .stat-card.kemiskinan .icon-wrapper {
            background: var(--kemiskinan-gradient);
        }

        .stat-card.lingkungan .icon-wrapper {
            background: var(--lingkungan-gradient);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: #1e3a5f;
            line-height: 1;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Data Card */
        .data-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .data-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .data-card-title {
            font-weight: 700;
            font-size: 1.1rem;
            color: #1e3a5f;
            margin: 0;
        }

        .data-card-body {
            padding: 1.5rem;
        }

        /* Tables */
        .table-modern {
            margin-bottom: 0;
        }

        .table-modern thead th {
            background: #f8fafc;
            border: none;
            padding: 1rem;
            font-weight: 600;
            color: #64748b;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-modern tbody td {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .table-modern tbody tr:hover {
            background: #f8fafc;
        }

        /* Buttons */
        .btn-primary-gradient {
            background: var(--primary-gradient);
            border: none;
            color: white;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        /* Forms */
        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 0.625rem 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        }

        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        /* Badge */
        .badge-stunting {
            background: var(--stunting-gradient);
        }

        .badge-kemiskinan {
            background: var(--kemiskinan-gradient);
        }

        .badge-lingkungan {
            background: var(--lingkungan-gradient);
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 12px;
        }

        /* Mobile Toggle */
        .sidebar-toggle {
            display: none;
        }

        /* User Avatar */
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #1a5f3c 0%, #0d4a2e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
        }

        .dropdown-toggle::after {
            margin-left: 0.5rem;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="sidebar-brand">
                <img src="{{ asset('images/logo-kabupaten-bandung.png') }}" alt="Logo Kabupaten Bandung">
                <span>
                    SI KUDA KEPANG
                    <small>Sistem Informasi Kumpulan Data Kecamatan Pangalengan</small>
                </span>
            </a>
        </div>

        <div class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>

            <!-- Portal Stunting -->
            @if(request()->routeIs('stunting.*'))
            <div class="portal-section">
                <div class="portal-header stunting">
                    <i class="bi bi-heart-pulse-fill"></i>
                    <span>Portal Stunting</span>
                </div>
                <div class="portal-menu">
                    <a href="{{ route('stunting.agregat') }}"
                        class="menu-item {{ request()->routeIs('stunting.agregat') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span>Data Agregat</span>
                    </a>
                    <a href="{{ route('stunting.anggaran') }}"
                        class="menu-item {{ request()->routeIs('stunting.anggaran*') ? 'active' : '' }}">
                        <i class="bi bi-cash-stack"></i>
                        <span>Anggaran Stunting</span>
                    </a>
                    <a href="{{ route('stunting.bnba.index') }}"
                        class="menu-item {{ request()->routeIs('stunting.bnba*') ? 'active' : '' }}">
                        <i class="bi bi-person-lines-fill"></i>
                        <span>By Name By Address</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Portal Kemiskinan -->
            @if(request()->routeIs('kemiskinan.*'))
            <div class="portal-section">
                <div class="portal-header kemiskinan">
                    <i class="bi bi-wallet2"></i>
                    <span>Portal Kemiskinan</span>
                </div>
                <div class="portal-menu">
                    <a href="{{ route('kemiskinan.index') }}"
                        class="menu-item {{ request()->routeIs('kemiskinan.index') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Overview</span>
                    </a>
                    <a href="{{ route('kemiskinan.desil') }}"
                        class="menu-item {{ request()->routeIs('kemiskinan.desil') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-line-fill"></i>
                        <span>Desil Kemiskinan</span>
                    </a>
                    <a href="{{ route('kemiskinan.pengangguran') }}"
                        class="menu-item {{ request()->routeIs('kemiskinan.pengangguran') ? 'active' : '' }}">
                        <i class="bi bi-person-x-fill"></i>
                        <span>Pengangguran</span>
                    </a>
                    <a href="{{ route('kemiskinan.pkh') }}"
                        class="menu-item {{ request()->routeIs('kemiskinan.pkh') ? 'active' : '' }}">
                        <i class="bi bi-house-heart-fill"></i>
                        <span>Realisasi PKH</span>
                    </a>
                    <a href="{{ route('kemiskinan.sembako') }}"
                        class="menu-item {{ request()->routeIs('kemiskinan.sembako') ? 'active' : '' }}">
                        <i class="bi bi-basket-fill"></i>
                        <span>Realisasi Sembako</span>
                    </a>
                    <a href="{{ route('kemiskinan.ak1') }}"
                        class="menu-item {{ request()->routeIs('kemiskinan.ak1') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-person-fill"></i>
                        <span>Data AK-1</span>
                    </a>

                </div>
            </div>
            @endif

            <!-- Portal Lingkungan -->
            @if(request()->routeIs('lingkungan.*'))
            <div class="portal-section">
                <div class="portal-header lingkungan">
                    <i class="bi bi-tree-fill"></i>
                    <span>Portal Lingkungan</span>
                </div>
                <div class="portal-menu">
                    <a href="{{ route('lingkungan.index') }}"
                        class="menu-item {{ request()->routeIs('lingkungan.index') ? 'active' : '' }}">
                        <i class="bi bi-images"></i>
                        <span>Dokumentasi</span>
                    </a>
                    <a href="{{ route('lingkungan.create') }}"
                        class="menu-item {{ request()->routeIs('lingkungan.create') ? 'active' : '' }}">
                        <i class="bi bi-plus-circle-fill"></i>
                        <span>Tambah Kegiatan</span>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <header class="header">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-link sidebar-toggle d-lg-none"
                    onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted d-none d-md-inline">
                    <i class="bi bi-calendar3"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>

                <!-- User Dropdown / Login -->
                @auth
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center gap-2"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div class="d-none d-sm-block text-start">
                            <div class="fw-semibold text-dark" style="font-size: 0.875rem;">{{ optional(Auth::user())->name }}
                            </div>
                            <div class="text-muted" style="font-size: 0.7rem;">
                                {{ ucwords(str_replace('_', ' ', optional(Auth::user())->role)) }}
                            </div>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li>
                            <div class="dropdown-header">
                                <strong>{{ optional(Auth::user())->name }}</strong><br>
                                <small class="text-muted">{{ optional(Auth::user())->email }}</small>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @else
                <a href="{{ route('login') }}" class="btn btn-primary-gradient px-4 d-flex align-items-center gap-2" style="font-size: 0.85rem;">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span class="d-none d-sm-block">Login Admin</span>
                </a>
                @endauth
            </div>
        </header>

        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-x-circle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @guest
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Remove any Add / Import / Export buttons that open modals
            document.querySelectorAll('[data-bs-toggle="modal"]').forEach(el => el.remove());
            
            // Remove 'Aksi' columns perfectly
            const tables = document.querySelectorAll('table');
            tables.forEach(table => {
                let actionIndex = -1;
                const headers = table.querySelectorAll('th');
                headers.forEach((th, idx) => {
                    if (th.textContent.trim() === 'Aksi' || th.textContent.trim() === 'Opsi') {
                        actionIndex = idx;
                        th.remove();
                    }
                });
                
                if (actionIndex > -1) {
                    const rows = table.querySelectorAll('tbody tr');
                    rows.forEach(tr => {
                        const tds = tr.children;
                        if (tds[actionIndex]) tds[actionIndex].remove();
                    });
                }
            });

            // Remove any forms that post data (except search/filter which usually use GET)
            document.querySelectorAll('form').forEach(form => {
                const method = form.getAttribute('method') ? form.getAttribute('method').toUpperCase() : 'GET';
                const hasMethodInput = form.querySelector('input[name="_method"]');
                if (method === 'POST' || hasMethodInput) {
                    form.remove();
                }
            });

            // Remove any links pointing to create/edit endpoints or have danger (delete) buttons
            document.querySelectorAll('a[href*="/create"], a[href*="/edit"], .btn-danger, .btn-warning').forEach(el => {
                if(!el.classList.contains('logout-link') && !el.getAttribute('href')?.includes('#')) {
                    el.remove();
                }
            });
        });
    </script>
    @endguest

    @stack('scripts')
</body>

</html>