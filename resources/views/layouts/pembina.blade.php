<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Portal Pembina</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --pembina-primary: #059669;
            --pembina-secondary: #10b981;
            --pembina-accent: #34d399;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f0fdfa;
        }

        .navbar-pembina {
            background: linear-gradient(135deg, var(--pembina-primary) 0%, var(--pembina-secondary) 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            background: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            position: sticky;
            top: 100px;
            height: fit-content;
        }

        .nav-pills .nav-link {
            border-radius: 10px;
            margin: 2px 0;
            transition: all 0.3s ease;
            color: #6b7280;
            font-weight: 500;
        }

        .nav-pills .nav-link:hover {
            background-color: rgba(5, 150, 105, 0.1);
            color: var(--pembina-primary);
            transform: translateX(5px);
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, var(--pembina-primary) 0%, var(--pembina-secondary) 100%);
            color: white;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .stats-card {
            padding: 1.5rem;
            border-radius: 15px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--pembina-primary) 0%, var(--pembina-secondary) 100%);
            border: none;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(5, 150, 105, 0.4);
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-pembina sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('pembina.dashboard') }}">
                <i class="fas fa-chalkboard-teacher me-2"></i>
                Portal Pembina
            </a>

            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                        data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=ffffff&color=059669&size=32"
                            class="rounded-circle me-2" width="32" height="32">
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
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
    <div class="container mt-4">
        <div class="row">
            <!-- SIDEBAR -->
            <div class="col-lg-3 mb-4">
                <div class="sidebar p-3">
                    <div class="text-center mb-4">
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=059669&color=ffffff&size=80"
                            class="rounded-circle mb-2" width="80" height="80">
                        <h6 class="mb-1">{{ auth()->user()->name }}</h6>
                        <small class="text-muted">Pembina Ekstrakurikuler</small>
                    </div>

                    <nav class="nav nav-pills flex-column">
                        <a class="nav-link {{ request()->routeIs('pembina.dashboard') ? 'active' : '' }}"
                            href="{{ route('pembina.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('pembina.anggota.*') ? 'active' : '' }}"
                            href="{{ route('pembina.anggota.index') }}">
                            <i class="fas fa-users me-2"></i>Kelola Anggota
                        </a>
                        <a class="nav-link {{ request()->routeIs('pembina.pendaftaran.*') ? 'active' : '' }}"
                            href="{{ route('pembina.pendaftaran.index') }}">
                            <i class="fas fa-clipboard-list me-2"></i>Kelola Pendaftaran
                        </a>
                        <a class="nav-link {{ request()->routeIs('pembina.kehadiran.*') ? 'active' : '' }}"
                            href="{{ route('pembina.kehadiran.index') }}">
                            <i class="fas fa-calendar-check me-2"></i>Input Kehadiran
                        </a>
                        <a class="nav-link {{ request()->routeIs('pembina.pengumuman.*') ? 'active' : '' }}"
                            href="{{ route('pembina.pengumuman.index') }}">
                            <i class="fas fa-bullhorn me-2"></i>Pengumuman
                        </a>
                    </nav>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="col-lg-9">
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
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
