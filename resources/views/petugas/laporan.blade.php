<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kinerja Petugas - Sistem Antrean</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

    <!-- Navbar Sederhana -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-file-earmark-text-fill me-2"></i> Laporan Antrean Petugas</a>
            <div class="d-flex">
                <a href="{{ route('petugas.dashboard') }}" class="btn btn-light btn-sm fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <!-- Filter Tanggal -->
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <form action="{{ route('petugas.laporan') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="tanggal_mulai" class="form-label fw-bold small text-muted">Dari Tanggal</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ $tanggalMulai }}">
                    </div>
                    <div class="col-md-4">
                        <label for="tanggal_selesai" class="form-label fw-bold small text-muted">Sampai Tanggal</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ $tanggalSelesai }}">
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1 fw-bold">
                            <i class="bi bi-filter me-1"></i> Filter Laporan
                        </button>
                        <button type="button" onclick="window.print()" class="btn btn-secondary fw-bold" title="Cetak Laporan">
                            <i class="bi bi-printer"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Kartu Statistik Ringkas -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-3">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 text-primary bg-primary bg-opacity-10 px-3 py-2 rounded-3 me-3">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Total Tiket Masuk</span>
                            <h3 class="fw-bold mb-0">{{ $totalSemua }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-3">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 text-success bg-success bg-opacity-10 px-3 py-2 rounded-3 me-3">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Selesai Dilayani</span>
                            <h3 class="fw-bold mb-0">{{ $totalDilayani }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Data Laporan -->
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-table me-2"></i> Rincian Riwayat Antrean</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase fs-7 text-secondary">
                            <tr>
                                <th class="py-3 px-4">No</th>
                                <th class="py-3">Nomor Antrean</th>
                                <th class="py-3">Loket / Petugas</th>
                                <th class="py-3">Layanan</th>
                                <th class="py-3">Status</th>
                                <th class="py-3 px-4">Waktu Selesai / Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporanTiket as $index => $tiket)
                                <tr>
                                    <td class="px-4 text-muted">{{ $index + 1 }}</td>
                                    <td><span class="fw-bold font-monospace text-primary fs-5">{{ $tiket->nomor_urut }}</span></td>
                                    <td>{{ $tiket->loket->nama_loket ?? '-' }}</td>
                                    <td>{{ $tiket->layanan->nama_layanan ?? '-' }}</td>
                                    <td>
                                        @if($tiket->status == 'selesai')
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-1.5 rounded-pill">Selesai</span>
                                        @elseif($tiket->status == 'dipanggil')
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-1.5 rounded-pill">Sedang Dipanggil</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-1.5 rounded-pill">{{ ucfirst($tiket->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 text-muted small">{{ $tiket->updated_at->format('d-m-Y H:i:s') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-folder-x fs-1 d-block mb-2"></i>
                                        Tidak ada data laporan antrean pada rentang tanggal tersebut.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>