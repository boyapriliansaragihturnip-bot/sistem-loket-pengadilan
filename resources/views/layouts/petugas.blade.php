<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Petugas') - Sistem Antrian Pengadilan</title>
    
    <!-- Bootstrap 5 CSS CDN (Atau gunakan asset lokal jika diinginkan) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    
    <!-- Navbar / Header Petugas yang Elegan -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm px-4 py-3 sticky-top border-bottom border-primary">
        <div class="container-fluid">
            
            <!-- Brand / Logo Identitas -->
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2 text-uppercase text-info" href="{{ route('petugas.dashboard') }}">
                <i class="bi bi-headset fs-4"></i>
                <span>Portal Loket Petugas</span>
            </a>

            <!-- Tombol Toggle untuk Mobile -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#petugasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigasi & Aksi Kanan -->
            <div class="collapse navbar-collapse" id="petugasNavbar">
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    
                    <!-- Tombol Pintasan Kembali ke Beranda Utama -->
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-semibold d-flex align-items-center gap-2">
                            <i class="bi bi-house-door"></i> Ke Beranda Utama
                        </a>
                    </li>

                    <li class="vr bg-secondary d-none d-lg-block opacity-50" style="height: 24px;"></li>

                    <!-- Info Identitas Petugas -->
                    <li class="nav-item text-end d-none d-sm-block">
                        <div class="fw-semibold text-white small">{{ Auth::user()->nama ?? 'Petugas Loket' }}</div>
                        <span class="badge bg-success bg-opacity-25 text-success small font-monospace">● Petugas Aktif</span>
                    </li>

                    <!-- Tombol Logout -->
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-4 fw-semibold shadow-sm d-flex align-items-center gap-1">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="container-fluid p-4 flex-grow-1">
        @include('components.alerts')
        @yield('content')
    </main>

    <!-- Footer Profesional -->
    <footer class="bg-white border-top py-3 text-center text-muted small mt-auto shadow-sm">
        <div class="container-fluid">
            &copy; {{ date('Y') }} Sistem Informasi Antrian Pengadilan Kelas IA. Local LAN Offline Ready.
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>