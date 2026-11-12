<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Antrean - {{ $tiket->nomor_urut }}</title>
    <!-- Bootstrap 5 CSS CDN (Atau gunakan asset lokal sesuai preferensi) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center py-5" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                
                <!-- Card Tiket Utama -->
                <div class="card shadow-lg border-0 rounded-4 text-center bg-white ticket-card">
                    <div class="card-body p-4 p-md-5">
                        
                        <!-- Header Instansi -->
                        <div class="mb-3">
                            <i class="bi bi-bank fs-1 text-primary"></i>
                            <h5 class="fw-bold text-dark text-uppercase mt-2 mb-1">{{ strtoupper($namaPengadilan ?? 'Pengadilan Negeri') }}</h5>
                            <p class="text-muted small border-bottom pb-3 mb-3 fw-semibold tracking-wider">TIKET ANTRIAN KUNJUNGAN</p>
                        </div>

                        <!-- Nomor Urut Antrean -->
                        <div class="my-4 py-4 bg-light rounded-4 border border-2 border-primary border-opacity-25 shadow-sm">
                            <span class="text-secondary fw-bold small d-block mb-1 text-uppercase">Nomor Antrean Anda</span>
                            <h1 class="display-1 fw-black text-primary mb-0" style="font-weight: 900; letter-spacing: -2px;">{{ $tiket->nomor_urut }}</h1>
                        </div>

                        <!-- Rincian Informasi Tiket -->
                        <div class="mb-4 text-start bg-body-tertiary p-3.5 rounded-4 small">
                            <div class="d-flex justify-content-between mb-2 border-bottom pb-2">
                                <span class="text-muted fw-semibold">Jenis Layanan:</span>
                                <strong class="text-dark">{{ $tiket->loket->layanan->nama_layanan ?? '-' }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2 border-bottom pb-2">
                                <span class="text-muted fw-semibold">Tujuan Loket:</span>
                                <strong class="text-dark">{{ $tiket->loket->nama_loket ?? '-' }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2 border-bottom pb-2">
                                <span class="text-muted fw-semibold">Tanggal:</span>
                                <span class="text-dark fw-semibold">{{ \Carbon\Carbon::parse($tiket->tanggal_tiket)->translatedFormat('d F Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted fw-semibold">Waktu Ambil:</span>
                                <span class="text-dark fw-semibold">{{ $tiket->created_at ? $tiket->created_at->format('H:i') : date('H:i') }} WIB</span>
                            </div>
                        </div>

                        <!-- Alert Petunjuk -->
                        <div class="alert alert-info border-0 small text-center mb-4 rounded-3 py-2">
                            <i class="bi bi-info-circle-fill me-1"></i> Silakan menunggu panggilan pada layar TV Display.
                        </div>

                        <!-- Tombol Aksi (Disembunyikan saat dicetak) -->
                        <div class="d-grid gap-2 d-print-none">
                            <button onclick="window.print()" class="btn btn-primary btn-lg fw-bold rounded-pill shadow-sm d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-printer"></i> Cetak Tiket
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary fw-semibold rounded-pill border-0">
                                &larr; Kembali ke Beranda Anjungan
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Script Opsional: Otomatis memicu dialog print saat halaman tiket terbuka di anjungan -->
    <script>
        // Uncomment baris di bawah jika ingin tiket langsung otomatis mencetak saat halaman terbuka
        // window.onload = function() { window.print(); }
    </script>

    <!-- CSS Khusus Percetakan (Print Styling) -->
    <style>
        @media print {
            body {
                background: white !important;
                color: black !important;
            }
            .ticket-card {
                box-shadow: none !important;
                border: 1px solid #dee2e6 !important;
                width: 100% !important;
                margin: 0 !important;
            }
            .d-print-none {
                display: none !important;
            }
        }
    </style>
</body>
</html>