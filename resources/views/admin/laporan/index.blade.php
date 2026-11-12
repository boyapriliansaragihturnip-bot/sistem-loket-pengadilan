@extends('layouts.admin')

@section('title', 'Laporan & Rekapitulasi Antrean')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
    <div>
        <h2 class="fw-bold text-dark text-uppercase tracking-tight fs-4 mb-1">Laporan Rekapitulasi Antrean</h2>
        <p class="text-muted small mb-0">Rekapitulasi data kunjungan dan pelayanan publik pengadilan.</p>
    </div>
    <button onclick="window.print()" class="btn btn-dark btn-sm rounded-pill px-4 py-2 fw-semibold shadow-sm d-flex align-items-center gap-2">
        <span>🖨️ Cetak Laporan</span>
    </button>
</div>

<!-- Filter Box (Hidden on Print) -->
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4 d-print-none bg-white">
    <form method="GET" action="{{ route('admin.laporan.index') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label text-uppercase fs-7 fw-bold text-secondary">Dari Tanggal</label>
            <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}" class="form-control rounded-3 bg-light border-0">
        </div>
        <div class="col-md-3">
            <label class="form-label text-uppercase fs-7 fw-bold text-secondary">Sampai Tanggal</label>
            <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}" class="form-control rounded-3 bg-light border-0">
        </div>
        <div class="col-md-3">
            <label class="form-label text-uppercase fs-7 fw-bold text-secondary">Loket Pelayanan</label>
            <select name="id_loket" class="form-select rounded-3 bg-light border-0">
                <option value="">Semua Loket</option>
                @foreach($loketList as $loket)
                    <option value="{{ $loket->id_loket }}" {{ $idLoket == $loket->id_loket ? 'selected' : '' }}>{{ $loket->nama_loket }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary rounded-3 w-100 fw-semibold shadow-sm">Tampilkan</button>
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-light rounded-3 w-100 fw-semibold text-secondary border">Reset</a>
        </div>
    </form>
</div>

<!-- Statistik Ringkasan -->
<div class="row row-cols-1 row-cols-md-4 g-3 mb-4">
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <span class="text-uppercase text-muted fw-bold fs-7 d-block mb-1">Total Tiket</span>
            <h3 class="fw-bold text-dark display-6 mb-0">{{ $totalTiket }}</h3>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-success border-4">
            <span class="text-uppercase text-success fw-bold fs-7 d-block mb-1">Selesai Dilayani</span>
            <h3 class="fw-bold text-success display-6 mb-0">{{ $totalSelesai }}</h3>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-warning border-4">
            <span class="text-uppercase text-warning fw-bold fs-7 d-block mb-1">Menunggu / Proses</span>
            <h3 class="fw-bold text-warning display-6 mb-0">{{ $totalMenunggu }}</h3>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-danger border-4">
            <span class="text-uppercase text-danger fw-bold fs-7 d-block mb-1">Dibatalkan</span>
            <h3 class="fw-bold text-danger display-6 mb-0">{{ $totalBatal }}</h3>
        </div>
    </div>
</div>

<!-- Tabel Rincian Laporan -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
    <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold text-dark mb-0 fs-6">Rincian Data Kunjungan Antrean</h5>
        <span class="text-muted small">Periode: {{ $tanggalMulai }} s/d {{ $tanggalSelesai }}</span>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light text-uppercase fs-7 text-muted fw-bold">
                <tr>
                    <th class="py-3 px-4">No</th>
                    <th class="py-3 px-4">Tanggal</th>
                    <th class="py-3 px-4">No Urut</th>
                    <th class="py-3 px-4">Tipe</th>
                    <th class="py-3 px-4">Layanan / Loket</th>
                    <th class="py-3 px-4 text-center">Status Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporanList as $index => $tiket)
                    <tr>
                        <td class="py-3 px-4 text-muted fw-semibold">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 text-secondary small">{{ $tiket->tanggal_tiket }}</td>
                        <td class="py-3 px-4 fw-bold text-dark fs-5">{{ $tiket->nomor_urut }}</td>
                        <td class="py-3 px-4">
                            <span class="badge rounded-pill {{ $tiket->tipe_antrean == 'online' ? 'bg-info bg-opacity-10 text-info' : 'bg-warning bg-opacity-10 text-warning' }} px-3 py-2 fw-semibold">
                                {{ ucfirst($tiket->tipe_antrean) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="fw-bold text-dark">{{ $tiket->loket->layanan->nama_layanan ?? '-' }}</div>
                            <div class="text-muted small">{{ $tiket->loket->nama_loket ?? '-' }}</div>
                        </td>
                        <td class="py-3 px-4 text-center">
                            @php
                                $badgeColor = match($tiket->status) {
                                    'menunggu' => 'bg-warning bg-opacity-10 text-warning',
                                    'dipanggil' => 'bg-info bg-opacity-10 text-info',
                                    'selesai' => 'bg-success bg-opacity-10 text-success',
                                    'batal' => 'bg-danger bg-opacity-10 text-danger',
                                    default => 'bg-secondary bg-opacity-10 text-secondary'
                                };
                            @endphp
                            <span class="badge rounded-pill {{ $badgeColor }} px-3 py-2 fw-semibold">
                                {{ ucfirst($tiket->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted small">Tidak ada data laporan pada rentang tanggal tersebut.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Print Styling Custom -->
@push('styles')
<style>
    @media print {
        body { background: white !important; color: black !important; }
        #sidebar-wrapper, .navbar, .d-print-none { display: none !important; }
        #page-content-wrapper { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        .card { border: none !important; box-shadow: none !important; }
    }
</style>
@endpush
@endsection