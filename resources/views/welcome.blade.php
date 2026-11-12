@extends('layouts.pengguna')

@section('title', 'Anjungan Antrean Mandiri')

@section('content')
<div class="container py-5">
    <!-- Header / Branding Pengadilan yang Elegan -->
    <div class="text-center mx-auto mb-5" style="max-width: 750px;">
        <div class="mb-3">
            <span class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 75px; height: 75px;">
                <i class="bi bi-bank fs-2"></i>
            </span>
        </div>
        <h1 class="fw-bold text-dark text-uppercase mb-2 tracking-tight" style="font-size: 1.85rem;">{{ $namaPengadilan ?? 'Pengadilan Negeri' }}</h1>
        <p class="text-muted">Silakan sentuh atau pilih salah satu jenis layanan di bawah ini untuk mengambil nomor antrean kunjungan Anda secara mandiri.</p>
    </div>

    <!-- Daftar Layanan Grid 4 Kolom dengan Ikon Bootstrap -->
    @if($layananList->isEmpty())
        <div class="alert alert-warning text-center py-5 rounded-4 shadow-sm border-0 bg-white">
            <i class="bi bi-exclamation-triangle fs-1 text-warning d-block mb-3"></i>
            <h5 class="fw-bold text-dark">Belum Ada Layanan Aktif</h5>
            <p class="text-muted mb-0 small">Belum ada layanan yang aktif saat ini. Silakan hubungi petugas informasi.</p>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @foreach($layananList as $index => $layanan)
                <div class="col">
                    <div class="card card-service h-100 p-4 d-flex flex-column justify-content-between shadow-sm border-0 bg-white rounded-4 position-relative overflow-hidden">
                        <!-- Aksen Warna di Atas Card -->
                        <div class="position-absolute top-0 start-0 w-100 bg-primary opacity-75" style="height: 4px;"></div>

                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="bg-primary bg-opacity-10 text-primary fw-bold fs-4 rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px;">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success px-2.5 py-1 rounded-pill small fw-semibold">
                                    <i class="bi bi-circle-fill" style="font-size: 6px;"></i> Aktif
                                </span>
                            </div>

                            <h4 class="fw-bold text-dark fs-5 mb-2">{{ $layanan->nama_layanan }}</h4>
                            <p class="text-muted small mb-4" style="line-height: 1.5;">
                                {{ Str::limit($layanan->deskripsi, 75, 'Ketuk untuk mengambil nomor antrean layanan ini.') }}
                            </p>
                        </div>
                        
                        <form method="POST" action="{{ route('queue.take') }}">
                            @csrf
                            <input type="hidden" name="id_layanan" value="{{ $layanan->id_layanan }}">
                            <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-pill fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2 text-white">
                                <i class="bi bi-ticket-perforated"></i>
                                <span>Ambil Nomor</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Panel Tampilan Publik & Kontrol Akses Iklan Khusus Admin -->
    <div class="mt-5 p-4 bg-dark text-white rounded-4 shadow d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 border border-secondary">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-info bg-opacity-10 text-info p-3 rounded-4 d-none d-sm-flex align-items-center justify-content-center">
                <i class="bi bi-display fs-2"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-1 fs-6">Perangkat Tampilan Publik & Informasi</h5>
                <p class="text-white-50 small mb-0">Buka layar televisi antrean real-time atau akses manajemen tayangan iklan.</p>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <!-- Tombol TV Display (Publik) -->
            <a href="/display" target="_blank" class="btn btn-info btn-sm rounded-pill px-4 fw-semibold text-dark shadow-sm d-flex align-items-center gap-2">
                <i class="bi bi-tv"></i> Layar TV Display
            </a>

            <!-- Tombol Layar Iklan TV (KHUSUS ADMIN) -->
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="/iklan" target="_blank" class="btn btn-outline-light btn-sm rounded-pill px-4 fw-semibold shadow-sm d-flex align-items-center gap-2">
                        <i class="bi bi-badge-ad"></i> Layar Iklan TV (Admin)
                    </a>
                @endif
            @endauth
        </div>
    </div>
</div>

@push('styles')
<style>
    .card-service {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .card-service:hover {
        transform: translateY(-4px);
        box-shadow: 0 .75rem 1.5rem rgba(0,0,0,.08)!important;
    }
</style>
@endpush
@endsection