<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SI KUDA KEPANG - Publik')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --stunting-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --kemiskinan-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --lingkungan-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar Publik */
        .public-navbar {
            background: linear-gradient(135deg, #0d4a2e 0%, #072c1a 100%);
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: white !important;
            font-weight: 800;
        }

        .navbar-brand img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .btn-login {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: white;
            color: #0d4a2e;
        }

        /* Footer */
        .public-footer {
            margin-top: auto;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 2rem 0;
            text-align: center;
            color: #64748b;
        }

        /* Cards and Elements (Reused from App) */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: none;
            height: 100%;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .stat-card.stunting::before { background: var(--stunting-gradient); }
        .stat-card.kemiskinan::before { background: var(--kemiskinan-gradient); }
        .stat-card.lingkungan::before { background: var(--lingkungan-gradient); }
        .stat-card.primary::before { background: var(--primary-gradient); }

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

        .stat-card.stunting .icon-wrapper { background: var(--stunting-gradient); }
        .stat-card.kemiskinan .icon-wrapper { background: var(--kemiskinan-gradient); }
        .stat-card.lingkungan .icon-wrapper { background: var(--lingkungan-gradient); }
        .stat-card.primary .icon-wrapper { background: var(--primary-gradient); }

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

        .hero-section {
            padding: 4rem 0 3rem;
            text-align: center;
        }

        .hero-title {
            font-weight: 800;
            color: #1e3a5f;
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }

        .hero-subtitle {
            color: #64748b;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="public-navbar">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('home') }}" class="navbar-brand text-decoration-none">
                <img src="{{ asset('images/logo-kabupaten-bandung.png') }}" alt="Logo">
                <div class="d-flex flex-column" style="gap: 2px;">
                    <span>SI KUDA KEPANG</span>
                    <span style="font-size: 0.7rem; font-weight: 400; opacity: 0.8;">Kecamatan Pangalengan</span>
                </div>
            </a>

            @auth
                <a href="{{ route('dashboard') }}" class="btn-login text-decoration-none">
                    <i class="bi bi-grid-1x2-fill me-2"></i>Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-login text-decoration-none">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login Sipil
                </a>
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="public-footer">
        <div class="container">
            <div class="fw-bold text-dark mb-1">SI KUDA KEPANG</div>
            <div>Sistem Informasi Kumpulan Data Kecamatan Pangalengan</div>
            <div class="mt-3" style="font-size: 0.8rem;">
                &copy; {{ date('Y') }} Pemerintah Kecamatan Pangalengan. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
