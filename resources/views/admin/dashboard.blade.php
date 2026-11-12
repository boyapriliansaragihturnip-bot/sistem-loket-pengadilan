@extends('layouts.admin')

@section('title', 'Dashboard Administrator')

@section('content')
<div class="mb-4">
    <h2 class="fw-black text-dark text-uppercase tracking-tight">Dashboard Utama Administrator</h2>
    <p class="text-muted small">Ringkasan statistik operasional sistem antrean pengadilan hari ini.</p>
</div>

<!-- Kartu Statistik Utama -->
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-4">
    <!-- Total Layanan -->
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-primary border-4">
            <div class="card-body">
                <span class="text-uppercase text-muted fw-bold small d-block mb-1">Total Layanan</span>
                <h2 class="fw-bold text-dark display-6 mb-0">{{ $totalLayanan }}</h2>
            </div>
        </div>
    </div>

    <!-- Total Loket -->
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-info border-4">
            <div class="card-body">
                <span class="text-uppercase text-muted fw-bold small d-block mb-1">Total Loket Aktif</span>
                <h2 class="fw-bold text-dark display-6 mb-0">{{ $totalLoket }}</h2>
            </div>
        </div>
    </div>

    <!-- Total Petugas -->
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-success border-4">
            <div class="card-body">
                <span class="text-uppercase text-muted fw-bold small d-block mb-1">Total Petugas</span>
                <h2 class="fw-bold text-dark display-6 mb-0">{{ $totalPetugas }}</h2>
            </div>
        </div>
    </div>

    <!-- Total Tiket Hari Ini -->
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-warning border-4">
            <div class="card-body">
                <span class="text-uppercase text-muted fw-bold small d-block mb-1">Antrean Masuk Hari Ini</span>
                <h2 class="fw-bold text-dark display-6 mb-0">{{ $totalTiketHariIni }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Status Tiket Hari Ini -->
<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
            <div class="card-body">
                <span class="text-muted fw-bold small text-uppercase">Menunggu Dilayani</span>
                <h1 class="fw-bold text-warning display-5 my-2">{{ $tiketMenunggu }}</h1>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
            <div class="card-body">
                <span class="text-muted fw-bold small text-uppercase">Sedang Dipanggil</span>
                <h1 class="fw-bold text-info display-5 my-2">{{ $tiketDipanggil }}</h1>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
            <div class="card-body">
                <span class="text-muted fw-bold small text-uppercase">Selesai Dilayani</span>
                <h1 class="fw-bold text-success display-5 my-2">{{ $tiketSelesai }}</h1>
            </div>
        </div>
    </div>
</div>
@endsection