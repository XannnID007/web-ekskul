<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Portal Siswa</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #10b981;
            --accent-color: #f59e0b;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --bg-light: #f8fafc;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-light);
        }

        /* Header Navbar */
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
        }

        /* Sidebar */
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
            color: var(--text-secondary);
            font-weight: 500;
        }

        .nav-pills .nav-link:hover {
            background-color: rgba(79, 70, 229, 0.1);
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        /* Cards */
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

        .card-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.4);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 10px;
            font-weight: 600;
        }

        /* Stats Cards */
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

        .stats-card .icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: static;
                margin-bottom: 20px;
            }
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('siswa.dashboard') }}">
                <i class="fas fa-graduation-cap me-2"></i>
                Portal Siswa
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                            role="button" data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=ffffff&color=4f46e5&size=32"
                                class="rounded-circle me-2" width="32" height="32">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('siswa.profil') }}"><i
                                        class="fas fa-user me-2"></i>Profil</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i
                                            class="fas fa-sign-out-alt me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="sidebar p-3">
                    <div class="text-center mb-4">
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=4f46e5&color=ffffff&size=80"
                            class="rounded-circle mb-2" width="80" height="80">
                        <h6 class="mb-1">{{ auth()->user()->name }}</h6>
                        <small class="text-muted">{{ auth()->user()->siswa->kelas ?? 'Siswa' }}</small>
                    </div>

                    <nav class="nav nav-pills flex-column">
                        <a class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}"
                            href="{{ route('siswa.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('siswa.ekstrakurikuler.*') ? 'active' : '' }}"
                            href="{{ route('siswa.ekstrakurikuler.index') }}">
                            <i class="fas fa-star me-2"></i>Ekstrakurikuler
                        </a>
                        <a class="nav-link {{ request()->routeIs('siswa.rekomendasi') ? 'active' : '' }}"
                            href="{{ route('siswa.rekomendasi') }}">
                            <i class="fas fa-magic me-2"></i>Rekomendasi
                        </a>
                        <a class="nav-link {{ request()->routeIs('siswa.pendaftaran') ? 'active' : '' }}"
                            href="{{ route('siswa.pendaftaran') }}">
                            <i class="fas fa-clipboard-list me-2"></i>Pendaftaran Saya
                        </a>
                        <a class="nav-link {{ request()->routeIs('siswa.kehadiran') ? 'active' : '' }}"
                            href="{{ route('siswa.kehadiran') }}">
                            <i class="fas fa-calendar-check me-2"></i>Kehadiran
                        </a>
                        <a class="nav-link {{ request()->routeIs('siswa.profil') ? 'active' : '' }}"
                            href="{{ route('siswa.profil') }}">
                            <i class="fas fa-user me-2"></i>Profil Saya
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-9">
                <!-- Alert Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="fade-in">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
