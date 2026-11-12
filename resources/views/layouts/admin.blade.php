<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') &mdash; Sistem Antrian Pengadilan</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }
        /* Sidebar Styling */
        #sidebar-wrapper {
            width: 270px;
            background: #0f172a;
            box-shadow: 1px 0 3px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        .sidebar-heading {
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #94a3b8;
            padding: 1.5rem 1.5rem 0.5rem 1.5rem;
            font-weight: 600;
        }
        .sidebar-menu .list-group-item {
            background: transparent;
            color: #cbd5e1;
            border: none;
            padding: 0.75rem 1.25rem;
            margin: 0.2rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
        }
        .sidebar-menu .list-group-item i {
            font-size: 1.1rem;
            color: #64748b;
            transition: color 0.2s ease;
        }
        .sidebar-menu .list-group-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
        }
        .sidebar-menu .list-group-item:hover i {
            color: #38bdf8;
        }
        .sidebar-menu .list-group-item.active {
            background: #2563eb !important;
            color: #ffffff !important;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        }
        .sidebar-menu .list-group-item.active i {
            color: #ffffff !important;
        }
        
        /* Navbar Styling */
        .navbar-admin {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            min-height: 70px;
        }
        
        /* Main Content */
        main {
            padding: 2rem;
        }

        /* Footer */
        footer {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 0.85rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex" id="wrapper" style="min-height: 100vh;">
        
        <!-- Sidebar -->
        <div class="d-flex flex-column flex-shrink-0" id="sidebar-wrapper">
            <div class="d-flex align-items-center px-4 py-4 border-bottom border-secondary border-opacity-10">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-2 d-flex align-items-center justify-content-center">
                        <i class="bi bi-bank fs-5"></i>
                    </div>
                    <div>
                        <span class="d-block fw-bold text-white fs-6 lh-1">PENGADILAN</span>
                        <span class="text-secondary small" style="font-size: 0.75rem;">Panel Administrasi</span>
                    </div>
                </div>
            </div>
            
            <!-- Tombol Pintasan Kembali ke Halaman Beranda Utama -->
            <div class="px-3 pt-3">
                <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm w-100 rounded-2 fw-semibold d-flex align-items-center justify-content-center gap-2 py-2 border-secondary text-light bg-opacity-10">
                    <i class="bi bi-house-door-fill text-info"></i> &larr; Ke Halaman Beranda
                </a>
            </div>
            
            <div class="sidebar-heading">MENU UTAMA</div>
            
            <div class="list-group list-group-flush sidebar-menu flex-grow-1">
                <a href="{{ route('admin.dashboard') }}" class="list-group-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
                
                <a href="{{ route('admin.layanan.index') }}" class="list-group-item {{ request()->is('admin/layanan*') ? 'active' : '' }}">
                    <i class="bi bi-folder2-open"></i> Kelola Layanan
                </a>
                
                <a href="{{ route('admin.loket.index') }}" class="list-group-item {{ request()->is('admin/loket*') ? 'active' : '' }}">
                    <i class="bi bi-display"></i> Kelola Loket
                </a>

                <a href="{{ route('admin.petugas.index') }}" class="list-group-item {{ request()->is('admin/petugas*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Kelola Petugas
                </a>

                <a href="{{ route('admin.penugasan.index') }}" class="list-group-item {{ request()->is('admin/penugasan*') ? 'active' : '' }}">
                    <i class="bi bi-link-45deg"></i> Penugasan Loket
                </a>

                <a href="{{ route('admin.tiket.index') }}" class="list-group-item {{ request()->is('admin/tiket*') ? 'active' : '' }}">
                    <i class="bi bi-ticket-detailed"></i> Kelola Tiket
                </a>

                <div class="sidebar-heading mt-2">LAPORAN & SISTEM</div>

                <a href="{{ route('admin.laporan.index') }}" class="list-group-item {{ request()->is('admin/laporan*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph"></i> Laporan Antrean
                </a>

                <a href="{{ route('admin.pengaturan.index') }}" class="list-group-item {{ request()->is('admin/pengaturan*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> Pengaturan
                </a>

                <a href="{{ route('admin.iklan.index') }}" class="list-group-item {{ request()->is('admin/iklan*') ? 'active' : '' }}">
                    <i class="bi bi-badge-ad"></i> Kelola Iklan TV
                </a>
            </div>
            
        </div>

        <!-- Page Content Wrapper -->
        <div id="page-content-wrapper" class="w-100 d-flex flex-column">
            
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-admin px-4">
                <div class="container-fluid p-0">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-secondary border px-2 py-1 fw-medium font-monospace small">LAN OFFLINE</span>
                        <span class="text-muted small d-none d-md-inline">&bull; Sistem Informasi Antrian Pengadilan</span>
                    </div>
                    
                    <div class="d-flex align-items-center ms-auto gap-3">
                        <div class="d-flex align-items-center gap-2 text-end">
                            <span class="text-secondary small">Masuk sebagai</span>
                            <span class="fw-semibold text-dark small">{{ Auth::user()->name ?? Auth::user()->nama ?? 'Administrator' }}</span>
                        </div>
                        <div class="vr bg-secondary opacity-25" style="height: 24px;"></div>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm px-3 fw-medium d-flex align-items-center gap-1">
                                <i class="bi bi-box-arrow-right"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Main Content Container -->
            <main class="flex-grow-1">
                @include('components.alerts')
                @yield('content')
            </main>

            <!-- Footer (Sudah Diperbaiki dan Dilengkapi) -->
            <footer class="py-3 px-4 text-center d-flex justify-content-between align-items-center">
                <span>&copy; {{ date('Y') }} Sistem Informasi Antrian Pengadilan</span>
                <span class="text-muted small">Local Area Network (LAN) Offline Ready</span>
            </footer>
            
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle (Sangat Penting untuk Modal Edit/Hapus & Dropdown) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>