<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TV Display Antrean - {{ $namaPengadilan ?? 'Pengadilan Negeri' }}</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bg-main: #edf2f7;
            --bg-card: #f8fafc;
            --border-color: #cbd5e1;
            --primary-color: #1e3a8a;
            --text-main: #1e293b;
        }

        body, html {
            height: 100vh;
            margin: 0;
            overflow: hidden;
            background-color: var(--bg-main);
            color: var(--text-main);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            user-select: none;
        }

        .text-huge {
            font-size: 8.5rem;
            font-weight: 800;
            letter-spacing: -2px;
            line-height: 1;
            color: var(--primary-color);
            transition: all 0.2s ease;
        }

        @keyframes pulse-call {
            0% { transform: scale(1); opacity: 1; color: #1e3a8a; }
            50% { transform: scale(1.08); opacity: 0.6; color: #2563eb; }
            100% { transform: scale(1); opacity: 1; color: #1e3a8a; }
        }
        .pulse-active {
            animation: pulse-call 0.6s ease-in-out 3;
        }

        .card-custom {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 1.25rem;
            box-shadow: 0 4px 15px -3px rgba(30, 41, 59, 0.08);
        }

        .marquee-container {
            background: #1e3a8a;
            color: #ffffff;
            overflow: hidden;
            white-space: nowrap;
            box-sizing: border-box;
        }
        .marquee-text {
            display: inline-block;
            animation: marquee 35s linear infinite;
        }
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: #e2e8f0;
            border-radius: 10px;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 10px;
        }
    </style>
</head>
<body class="d-flex flex-column h-100">

    <!-- Header / Navbar TV -->
    <header class="px-4 py-3 border-bottom shadow-sm" style="background-color: #f8fafc; border-color: #cbd5e1 !important;">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2.5 rounded-3 fs-3 d-flex align-items-center justify-content-center border" style="width: 48px; height: 48px; background-color: #edf2f7; color: #1e3a8a; border-color: #cbd5e1;">
                    <i class="bi bi-bank2"></i>
                </div>
                <div>
                    <h1 class="h5 fw-bold text-uppercase mb-0 tracking-wider" style="color: #1e3a8a;">
                        {{ $namaPengadilan ?? 'Pengadilan Negeri Kelas IA' }}
                    </h1>
                    <span class="text-muted small">Sistem Informasi Antrean Sidang & Pelayanan Terpadu</span>
                </div>
            </div>
            
            <div class="text-end px-4 py-2 rounded-3 shadow-sm border" style="background-color: #edf2f7; border-color: #cbd5e1;">
                <div id="clock" class="fs-4 fw-bold font-monospace tracking-wider mb-0" style="color: #1e3a8a;">00:00:00</div>
                <small class="text-muted" style="font-size: 0.75rem;">{{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</small>
            </div>
        </div>
    </header>

    <!-- Main Content Grid -->
    <main class="container-fluid p-4 flex-grow-1 d-flex overflow-hidden">
        <div class="row g-4 w-100 m-0">
            
            <!-- Kolom Kiri: Panggilan Utama -->
            <div class="col-lg-8 h-100 ps-0">
                <div class="card card-custom h-100 p-4 d-flex flex-column justify-content-between position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge px-3 py-2 rounded-pill fw-semibold text-uppercase tracking-wider fs-6 border" style="background-color: #e2e8f0; color: #1e3a8a; border-color: #cbd5e1;">
                            <span class="spinner-grow spinner-grow-sm me-2" style="color: #2563eb;" role="status"></span> Panggilan Utama
                        </span>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Layar Informasi Publik</span>
                    </div>

                    <div class="text-center my-auto py-3">
                        <span class="text-uppercase text-secondary fw-bold tracking-widest d-block mb-2" style="font-size: 0.85rem; letter-spacing: 4px;">
                            NOMOR ANTREAN DIPANGGIL
                        </span>
                        
                        <div id="displayNomorUtama" class="text-huge font-monospace my-2">
                            {{ $tiketDipanggil->nomor_urut ?? '---' }}
                        </div>
                        
                        <div class="mt-3">
                            <div class="d-inline-block px-5 py-2.5 rounded-pill border shadow-sm" style="background-color: #edf2f7; border-color: #cbd5e1;">
                                <div id="displayLoketUtama" class="fs-3 fw-bold text-uppercase" style="color: #1e3a8a;">
                                    {{ $tiketDipanggil->loket->nama_loket ?? 'Silakan Menunggu Panggilan' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border rounded-3 py-3 px-4 text-center text-secondary small d-flex align-items-center justify-content-center gap-2" style="background-color: #edf2f7; border-color: #cbd5e1;">
                        <i class="bi bi-info-circle fs-5" style="color: #2563eb;"></i>
                        <span>Harap menyiapkan kartu identitas atau dokumen perkara Anda sebelum menuju ke loket pelayanan.</span>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Daftar Loket Aktif -->
            <div class="col-lg-4 h-100 pe-0">
                <div class="card card-custom h-100 d-flex flex-column overflow-hidden">
                    <div class="card-header border-bottom py-3 px-4 d-flex justify-content-between align-items-center" style="background-color: #f8fafc; border-color: #cbd5e1 !important;">
                        <h6 class="fw-bold mb-0 d-flex align-items-center gap-2 text-uppercase tracking-wider" style="font-size: 0.85rem; color: #1e3a8a;">
                            <span class="badge rounded-circle bg-success p-1"></span> Status Loket Aktif
                        </h6>
                        <span class="badge bg-secondary bg-opacity-25 text-secondary px-2.5 py-1" style="font-size: 0.7rem;">LIVE LAN</span>
                    </div>

                    <div id="loketContainer" class="card-body p-3 overflow-auto custom-scroll flex-grow-1" style="background-color: #f8fafc;">
                        @foreach($loketList as $loket)
                            <div class="p-3 mb-3 rounded-3 border d-flex justify-content-between align-items-center shadow-sm" style="background-color: #edf2f7; border-color: #cbd5e1 !important;">
                                <div>
                                    <h6 class="fw-bold mb-1 fs-6" style="color: #1e3a8a;">{{ $loket->nama_loket }}</h6>
                                    <span class="text-muted d-block" style="font-size: 0.75rem;">{{ $loket->layanan->nama_layanan ?? 'Pelayanan Umum' }}</span>
                                </div>
                                <div class="text-end border px-3 py-2 rounded-2 shadow-sm" style="min-width: 90px; background-color: #f8fafc; border-color: #cbd5e1 !important;">
                                    <span class="fs-3 fw-bold text-success loket-nomor-val font-monospace" data-loket="{{ $loket->id_loket }}">
                                        {{ $loket->nomor_aktif ?? '---' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer Berjalan -->
    <footer class="py-2.5 marquee-container border-top shadow-sm" style="border-color: #1d4ed8 !important;">
        <div class="marquee-text fw-bold text-uppercase tracking-wider" style="font-size: 0.8rem;">
            ⚡ Selamat Datang di Sistem Informasi Antrian Terpadu Pengadilan Negeri &bull; Utamakan Budaya Antre, Transparansi, dan Integritas Pelayanan Publik &bull; Silakan Menunggu Panggilan Petugas Sesuai Nomor Antrean Anda ⚡
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Jam Digital Real-Time
        setInterval(() => {
            const now = new Date();
            document.getElementById('clock').innerText = now.toTimeString().split(' ')[0];
        }, 1000);

        let lastPlayedTicketId = null;
        let lastNomorUrut = null;

        // Fungsi Suara Pemanggilan yang Aman Murni Berbunyi 1 Kali
        function playSpeech(nomor, namaLoket) {
            if (!('speechSynthesis' in window)) return;

            // Hentikan suara yang sedang berjalan agar bersih
            window.speechSynthesis.cancel();

            let formattedNomor = nomor.split('').map(char => {
                if (char === '-') return ' ';
                return char;
            }).join(' ');

            let text = `Nomor antrean, ${formattedNomor}, silakan menuju ke, ${namaLoket}`;
            
            let utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'id-ID';
            utterance.rate = 0.85; 

            // Efek kedip visual pada nomor utama
            let nomorEl = document.getElementById('displayNomorUtama');
            if (nomorEl) {
                nomorEl.classList.add('pulse-active');
                setTimeout(() => {
                    nomorEl.classList.remove('pulse-active');
                }, 1800);
            }

            // Eksekusi pemutaran suara
            window.speechSynthesis.speak(utterance);
        }

        // Polling Data Real-Time setiap 1 detik
        function fetchQueueData() {
            fetch('/api/display/data')
                .then(response => response.json())
                .then(data => {
                    if (data.loket_list) {
                        data.loket_list.forEach(loket => {
                            let el = document.querySelector(`.loket-nomor-val[data-loket="${loket.id_loket}"]`);
                            if (el) {
                                el.innerText = loket.nomor_aktif || '---';
                            }
                        });
                    }

                    if (data.latest_dipanggil) {
                        let currentId = data.latest_dipanggil.id_tiket;
                        let currentNomor = data.latest_dipanggil.nomor_urut;
                        let currentLoket = data.latest_dipanggil.nama_loket;

                        document.getElementById('displayNomorUtama').innerText = currentNomor;
                        document.getElementById('displayLoketUtama').innerText = currentLoket;

                        // PENGAMANAN UTAMA:
                        // Suara HANYA BERBUNYI jika ID Tiket Berubah (Panggil Berikutnya) 
                        // ATAU Nomor Urut Berubah. Jika data dari server sama persis, abaikan mutlak!
                        if (lastPlayedTicketId !== currentId || lastNomorUrut !== currentNomor) {
                            lastPlayedTicketId = currentId;
                            lastNomorUrut = currentNomor;
                            
                            playSpeech(currentNomor, currentLoket);
                        }
                    }
                })
                .catch(err => console.error('Error fetching display data:', err));
        }

        // Jalankan polling setiap 1 detik
        setInterval(fetchQueueData, 1000);
    </script>
</body>
</html>