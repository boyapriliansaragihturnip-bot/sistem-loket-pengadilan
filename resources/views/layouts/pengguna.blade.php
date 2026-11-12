<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Pengunjung') - Sistem Antrian Pengadilan</title>
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    @stack('styles')
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    
    <!-- Navbar Pengguna -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm px-4 py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2 text-uppercase tracking-wider text-info" href="{{ route('home') }}">
                <i class="bi bi-bank fs-4"></i>
                <span>Pengadilan Negeri</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    @auth
                        <!-- Tombol Dashboard Dinamis Berdasarkan Role -->
                        @php
                            $role = Auth::user()->role;
                        @endphp

                        <li class="nav-item">
                            @if($role === 'admin')
                                <a class="btn btn-primary btn-sm rounded-pill px-3 fw-semibold shadow-sm" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard Admin
                                </a>
                            @elseif($role === 'petugas')
                                <a class="btn btn-success btn-sm rounded-pill px-3 fw-semibold shadow-sm" href="{{ route('petugas.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard Petugas
                                </a>
                            @else
                                <a class="btn btn-outline-light btn-sm rounded-pill px-3 fw-semibold" href="{{ route('pengguna.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard Saya
                                </a>
                            @endif
                        </li>

                        <li class="nav-item text-light px-2 small d-none d-lg-block">
                            Halo, <strong class="text-info">{{ Auth::user()->nama ?? 'Pengguna' }}</strong>
                        </li>

                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-4 fw-semibold shadow-sm">
                                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="btn btn-outline-light btn-sm rounded-pill px-4 fw-semibold" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm rounded-pill px-4 fw-semibold shadow-sm" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i> Registrasi
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="container py-5 flex-grow-1">
        @include('components.alerts')
        @yield('content')
    </main>

    <!-- Footer Profesional -->
    <footer class="bg-white border-top py-4 text-center text-muted small shadow-sm mt-auto">
        <div class="container">
            <p class="mb-1 fw-semibold text-dark">&copy; {{ date('Y') }} Sistem Informasi Antrian Pengadilan Kelas IA.</p>
            <p class="mb-0 text-secondary small">Sistem Antrean Publik Terpadu.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>