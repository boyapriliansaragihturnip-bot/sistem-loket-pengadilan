@extends('layouts.pengguna')

@section('title', 'Dashboard Pengguna')

@section('content')
<div class="container py-4">
    <!-- Welcome Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white p-4 p-md-5">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <span class="badge bg-white text-primary px-3 py-1.5 fw-semibold rounded-pill mb-3">Portal Pengguna</span>
                        <h1 class="fw-bold fs-2 mb-2">Selamat Datang, {{ Auth::user()->nama ?? 'Masyarakat' }}!</h1>
                        <p class="text-white-50 mb-0">Silakan ambil nomor antrean online atau pantau status antrean Anda di Pengadilan dengan mudah dan cepat.</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-4 mt-md-0">
                        <a href="#" class="btn btn-light text-primary fw-semibold px-4 py-2.5 rounded-pill shadow-sm">
                            <i class="bi bi-ticket-perforated me-1"></i> Ambil Antrean Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats / Info Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-4 fs-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Antrean Aktif</span>
                        <h3 class="fw-bold text-dark mb-0">0</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-4 fs-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Selesai Dilayani</span>
                        <h3 class="fw-bold text-dark mb-0">0</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-info bg-opacity-10 text-info p-3 rounded-4 fs-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Status Sistem</span>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 fw-semibold">Online / LAN</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Ticket Section -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0 fs-6">Riwayat Tiket Antrean Anda</h5>
            <span class="text-muted small">Hari Ini</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small text-uppercase fw-bold">
                        <tr>
                            <th class="py-3 ps-4">Nomor Urut</th>
                            <th class="py-3">Layanan / Loket</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3">Waktu & Tanggal</th>
                            <th class="py-3 text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Contoh baris kosong jika belum ada data -->
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-ticket-detailed display-6 d-block mb-2 text-secondary opacity-50"></i>
                                Anda belum memiliki riwayat tiket antrean aktif.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection