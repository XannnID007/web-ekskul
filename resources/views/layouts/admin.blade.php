<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Admin Panel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --admin-primary: #1e40af;
            --admin-secondary: #3b82f6;
            --admin-accent: #60a5fa;
            --sidebar-width: 280px;
            --navbar-height: 70px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 0;
        }

        /* NAVBAR ADMIN */
        .navbar-admin {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--navbar-height);
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-secondary) 100%);
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* SIDEBAR ADMIN */
        .sidebar-admin {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            z-index: 1001;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.1);
        }

        .sidebar-brand h5 {
            margin: 0;
            font-weight: 700;
        }

        .sidebar-nav {
            padding: 20px 15px;
        }

        .nav-section-title {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 20px 0 10px 0;
            font-weight: 600;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9);
            padding: 12px 15px;
            border-radius: 8px;
            margin: 2px 0;
            transition: all 0.3s ease;
            font-weight: 500;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .nav-link i {
            width: 20px;
            margin-right: 12px;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            padding: 20px;
            min-height: calc(100vh - var(--navbar-height));
        }

        /* CARDS */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .stats-card {
            padding: 25px;
            border-radius: 15px;
            color: white;
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .stats-card .icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }

        .stats-card .number {
            font-size: 2rem;
            font-weight: 700;
            margin: 10px 0 5px 0;
        }

        .stats-card.primary {
            --gradient-start: #1e40af;
            --gradient-end: #3b82f6;
        }

        .stats-card.success {
            --gradient-start: #059669;
            --gradient-end: #10b981;
        }

        .stats-card.warning {
            --gradient-start: #d97706;
            --gradient-end: #f59e0b;
        }

        .stats-card.info {
            --gradient-start: #0891b2;
            --gradient-end: #06b6d4;
        }

        /* BUTTONS */
        .btn-primary {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-secondary) 100%);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 64, 175, 0.4);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar-admin {
                transform: translateX(-100%);
            }

            .navbar-admin {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- SIDEBAR -->
    <div class="sidebar-admin">
        <div class="sidebar-brand">
            <h5><i class="fas fa-cogs me-2"></i>ADMIN PANEL</h5>
            <small>System Manager</small>
        </div>

        <div class="sidebar-nav">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>Dashboard Sistem
            </a>

            <div class="nav-section-title">KELOLA DATA MASTER</div>
            <a class="nav-link {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}"
                href="{{ route('admin.siswa.index') }}">
                <i class="fas fa-users"></i>Kelola Siswa
            </a>
            <a class="nav-link {{ request()->routeIs('admin.pembina.*') ? 'active' : '' }}"
                href="{{ route('admin.pembina.index') }}">
                <i class="fas fa-chalkboard-teacher"></i>Kelola Pembina
            </a>
            <a class="nav-link {{ request()->routeIs('admin.ekstrakurikuler.*') ? 'active' : '' }}"
                href="{{ route('admin.ekstrakurikuler.index') }}">
                <i class="fas fa-star"></i>Kelola Ekstrakurikuler
            </a>
            <a class="nav-link {{ request()->routeIs('admin.kriteria.*') ? 'active' : '' }}"
                href="{{ route('admin.kriteria.index') }}">
                <i class="fas fa-sliders-h"></i>Kelola Kriteria
            </a>
            <a class="nav-link {{ request()->routeIs('admin.penilaian.*') ? 'active' : '' }}"
                href="{{ route('admin.penilaian.index') }}">
                <i class="fas fa-calculator"></i>Kelola Penilaian
            </a>

            <div class="nav-section-title">MONITORING SISTEM</div>
            <a class="nav-link {{ request()->routeIs('admin.monitor.*') ? 'active' : '' }}" href="">
                <i class="fas fa-eye"></i>Monitor Aktivitas
            </a>

            <div class="nav-section-title">LAPORAN & ANALISIS</div>
            <a class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" href="">
                <i class="fas fa-chart-line"></i>Generate Laporan
            </a>

            <div class="nav-section-title">PENGATURAN</div>
            <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="">
                <i class="fas fa-cog"></i>System Settings
            </a>
        </div>
    </div>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-admin">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold">
                @yield('page-title', 'Dashboard Sistem')
            </span>

            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                        data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=ffffff&color=1e40af&size=32"
                            class="rounded-circle me-2" width="32" height="32">
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                                <i class="fas fa-cog me-2"></i>Settings
                            </a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <!-- ALERTS -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
