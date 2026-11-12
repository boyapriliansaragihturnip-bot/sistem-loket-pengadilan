@extends('layouts.petugas')

@section('title', 'Dashboard Petugas Loket')

@section('content')
<div class="container-fluid px-0">

    @if(isset($error))
        <!-- State Error / Belum Ditugaskan -->
        <div class="row justify-content-center my-5">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                    <div class="mb-3 text-warning">
                        <i class="bi bi-exclamation-triangle fs-1"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-2">Perhatian</h3>
                    <p class="text-muted small mb-4">{{ $error }}</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-dark btn-sm rounded-pill px-4 fw-semibold shadow-sm">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <!-- Header Info & Tombol Akses Laporan -->
        <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-4 shadow-sm">
            <div>
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-building me-2 text-primary"></i>Loket: {{ $loket->nama_loket ?? '-' }}
                </h5>
                <span class="text-muted small">Layanan: {{ $loket->layanan->nama_layanan ?? 'Umum' }}</span>
            </div>
            <div>
                <!-- Tombol Menuju Halaman Laporan Kinerja Petugas -->
                <a href="{{ route('petugas.laporan') }}" class="btn btn-outline-primary btn-sm fw-semibold rounded-pill px-3 shadow-sm">
                    <i class="bi bi-file-earmark-text-fill me-1"></i> Lihat Laporan Kinerja
                </a>
            </div>
        </div>

        <!-- Grid Utama Dashboard Petugas -->
        <div class="row g-4">
            
            <!-- Kolom Kiri: Panel Kontrol Panggilan & Nomor Aktif (2 Kolom Bootstrap) -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white h-100">
                    
                    <!-- Top Accent Bar -->
                    <div class="bg-primary py-1 w-100"></div>

                    <div class="card-body p-4.5 p-md-5 d-flex flex-column justify-content-between text-center">
                        
                        <div>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1.5 rounded-pill fw-bold text-uppercase tracking-wider mb-3">
                                <i class="bi bi-mic-fill me-1"></i> Status Loket Aktif
                            </span>
                            
                            <h6 class="text-muted small fw-semibold text-uppercase mb-1">Nomor Antrean Sedang Dilayani</h6>
                            
                            <!-- Display Nomor Antrean Besar -->
                            <div class="my-4 py-4 bg-light rounded-4 border border-2 border-primary border-opacity-10 shadow-sm mx-auto" style="max-width: 450px;">
                                <h1 class="display-1 fw-black text-primary mb-0" style="font-weight: 900; letter-spacing: -2px;">
                                    {{ $tiketAktif->nomor_urut ?? '---' }}
                                </h1>
                                <p class="text-secondary small mt-2 mb-0 fw-semibold">
                                    @if($tiketAktif)
                                        Tipe: <span class="text-dark uppercase">{{ $tiketAktif->tipe_antrean ?? 'Umum' }}</span> &bull; Status: <span class="text-success fw-bold">Sedang Dilayani</span>
                                    @else
                                        Belum ada antrean yang dipanggil saat ini.
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Tombol Aksi Operasional (Panggil, Ulang, Selesai, Batal) -->
                        <div class="pt-4 border-top border-light mt-4">
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                
                                <!-- Tombol Panggil Berikutnya -->
                                <form method="POST" action="{{ route('petugas.panggil.berikutnya') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-primary px-4 py-2.5 rounded-pill fw-semibold shadow-sm d-flex align-items-center gap-2">
                                        <i class="bi bi-megaphone-fill"></i> Panggil Berikutnya
                                    </button>
                                </form>

                                @if($tiketAktif)
                                    <!-- Tombol Panggil Ulang -->
                                    <form method="POST" action="{{ route('petugas.panggil.ulang', $tiketAktif->id_tiket) }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-light border px-3 py-2.5 rounded-pill fw-semibold shadow-sm d-flex align-items-center gap-1 text-dark hover-bg-secondary">
                                            <i class="bi bi-arrow-clockwise"></i> Panggil Ulang
                                        </button>
                                    </form>

                                    <!-- Tombol Selesaikan -->
                                    <form method="POST" action="{{ route('petugas.panggil.selesai', $tiketAktif->id_tiket) }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-success px-4 py-2.5 rounded-pill fw-semibold shadow-sm d-flex align-items-center gap-2 text-white">
                                            <i class="bi bi-check-circle-fill"></i> Selesaikan
                                        </button>
                                    </form>

                                    <!-- Tombol Batalkan -->
                                    <form method="POST" action="{{ route('petugas.panggil.batal', $tiketAktif->id_tiket) }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger px-3 py-2.5 rounded-pill fw-semibold d-flex align-items-center gap-1">
                                            <i class="bi bi-x-circle"></i> Batalkan
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Daftar Antrean Menunggu (1 Kolom Bootstrap) -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white h-100 d-flex flex-column" style="max-height: 520px;">
                    
                    <div class="card-header bg-white border-bottom py-3.5 px-4 d-flex justify-content-between align-items-center rounded-top-4">
                        <h5 class="fw-bold text-dark mb-0 fs-6 d-flex align-items-center gap-2">
                            <i class="bi bi-people-fill text-primary"></i> Antrean Menunggu
                        </h5>
                        <span class="badge bg-warning bg-opacity-10 text-warning px-2.5 py-1 rounded-pill fw-bold">
                            {{ $antreanMenunggu->count() }} Orang
                        </span>
                    </div>

                    <!-- List Antrean dengan Scroll -->
                    <div class="card-body p-3 overflow-auto flex-grow-1" style="max-height: 420px;">
                        @forelse($antreanMenunggu as $antrean)
                            <div class="p-3 mb-2 rounded-3 bg-light border border-light d-flex justify-content-between align-items-center transition-all hover-shadow">
                                <div>
                                    <h4 class="fw-black text-dark mb-1 fs-4 font-monospace">{{ $antrean->nomor_urut }}</h4>
                                    <span class="text-muted small d-block">
                                        <i class="bi bi-clock me-1"></i> Jam: {{ $antrean->jam_prakiraan ?? '08:00' }} WIB
                                    </span>
                                </div>
                                <span class="badge bg-warning bg-opacity-25 text-dark px-2.5 py-1 rounded-pill small fw-semibold">
                                    Menunggu
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted small">
                                <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                                Belum ada antrean yang menunggu saat ini.
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>

        </div>
    @endif

</div>

@push('styles')
<style>
    .fw-black {
        font-weight: 800 !important;
    }
    .hover-shadow:hover {
        background-color: #f1f5f9 !important;
        transition: background-color 0.2s ease;
    }
</style>
@endpush
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const formPanggilUlang = document.querySelector('form[action*="panggil-ulang"]');
        
        if (formPanggilUlang) {
            const btn = formPanggilUlang.querySelector('button');
            const cooldownTime = 180; // Jeda waktu 3 menit (180 detik)
            
            const lastClicked = localStorage.getItem('panggil_ulang_cooldown');
            if (lastClicked) {
                const elapsed = Math.floor((Date.now() - parseInt(lastClicked)) / 1000);
                if (elapsed < cooldownTime) {
                    startCooldown(cooldownTime - elapsed, btn);
                }
            }

            formPanggilUlang.addEventListener('submit', function (e) {
                if (btn.classList.contains('disabled')) {
                    e.preventDefault();
                    return false;
                }
                
                localStorage.setItem('panggil_ulang_cooldown', Date.now().toString());
                startCooldown(cooldownTime, btn);
            });
        }

        function startCooldown(duration, button) {
            button.classList.add('disabled', 'opacity-50');
            button.setAttribute('disabled', 'true');
            
            let originalHtml = button.innerHTML;
            let countdown = duration;
            
            let minutes = Math.floor(countdown / 60);
            let seconds = countdown % 60;
            button.innerHTML = `<i class="bi bi-clock-history"></i> Jeda (${minutes}:${seconds < 10 ? '0' : ''}${seconds})`;
            
            let timer = setInterval(() => {
                countdown--;
                minutes = Math.floor(countdown / 60);
                seconds = countdown % 60;
                
                button.innerHTML = `<i class="bi bi-clock-history"></i> Jeda (${minutes}:${seconds < 10 ? '0' : ''}${seconds})`;
                
                if (countdown <= 0) {
                    clearInterval(timer);
                    localStorage.removeItem('panggil_ulang_cooldown');
                    button.classList.remove('disabled', 'opacity-50');
                    button.removeAttribute('disabled');
                    button.innerHTML = originalHtml;
                }
            }, 1000);
        }
    });
</script>
@endpush
@endsection