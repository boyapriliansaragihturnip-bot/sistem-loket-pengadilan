<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layar Iklan TV - Sistem Antrian Pengadilan</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        html, body {
            width: 100vw;
            height: 100vh;
            margin: 0;
            padding: 0;
            background-color: #000;
            overflow: hidden;
        }
        .video-fullscreen-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
        }
        video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .no-video-alert {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            text-align: center;
            font-family: sans-serif;
        }
        /* Tombol Overlay Interaksi Pertama */
        #start-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.85);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <!-- Overlay Tombol Klik Pertama untuk Membuka Blokir Audio Browser -->
    <div id="start-overlay">
        <div class="card bg-dark border-secondary p-5 text-center shadow-lg rounded-4" style="max-width: 500px;">
            <div class="mb-3 text-info fs-1">
                <i class="bi bi-play-circle-fill"></i>
            </div>
            <h3 class="fw-bold mb-2 text-white">Layar Iklan & Suara Siap</h3>
            <p class="text-secondary small mb-4">Klik tombol di bawah ini sekali saja untuk memulai tayangan video beserta suaranya secara penuh di monitor.</p>
            <button class="btn btn-primary btn-lg fw-bold px-4 py-3 rounded-pill shadow">
                <i class="bi bi-volume-up-fill me-2"></i> Mulai Tayangan & Suara
            </button>
        </div>
    </div>

    @if($activeIklan->isEmpty())
        <!-- Tampilan Jika Belum Ada Video Diunggah Admin -->
        <div class="no-video-alert">
            <h2 class="fw-bold mb-2">Layar Iklan Siap</h2>
            <p class="text-white-50 small mb-0">Belum ada video tayangan yang diunggah oleh Admin.</p>
        </div>
    @else
        <!-- Pemutar Video Full Screen dengan Carousel -->
        <div id="carouselIklan" class="carousel slide carousel-fade w-100 h-100" data-bs-ride="carousel" data-bs-interval="false">
            <div class="carousel-inner w-100 h-100">
                @foreach($activeIklan as $index => $iklan)
                    <div class="carousel-item w-100 h-100 {{ $index === 0 ? 'active' : '' }}">
                        <div class="video-fullscreen-container">
                            <!-- Atribut disablepictureinpicture & controlslist ditambahkan untuk menghilangkan tombol melayang browser -->
                            <video class="active-video" playsinline disablepictureinpicture controlslist="nodownload nofullscreen noremoteplayback" {{ $activeIklan->count() === 1 ? 'loop' : '' }}>
                                <source src="{{ asset($iklan->file_media) }}" type="video/mp4">
                                Browser Anda tidak mendukung pemutaran video.
                            </video>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const overlay = document.getElementById('start-overlay');
            const myCarousel = document.getElementById('carouselIklan');
            const videos = document.querySelectorAll('video');
            const totalVideos = videos.length;

            if (!myCarousel || totalVideos === 0) {
                if (overlay) overlay.style.display = 'none';
                return;
            }

            const bootstrapCarousel = new bootstrap.Carousel(myCarousel, {
                interval: false,
                ride: false
            });

            // Saat tombol overlay diklik, hilangkan overlay, aktifkan suara, dan mulai putar video
            overlay.addEventListener('click', function() {
                overlay.style.display = 'none';

                // Putar video pertama dengan suara aktif (muted = false)
                videos[0].muted = false;
                videos[0].currentTime = 0;
                videos[0].play().catch(err => console.log("Error playing video:", err));
            });

            // Jika video lebih dari 1, tangkap event 'ended' untuk lanjut ke video berikutnya secara berurutan
            if (totalVideos > 1) {
                videos.forEach((video) => {
                    video.addEventListener('ended', function() {
                        bootstrapCarousel.next();
                    });
                });

                // Saat slide carousel bergeser, putar video berikutnya dengan suara aktif
                myCarousel.addEventListener('slid.bs.carousel', function (event) {
                    videos.forEach(vid => {
                        vid.pause();
                        vid.currentTime = 0;
                    });

                    let activeVideo = event.relatedTarget.querySelector('video');
                    if (activeVideo) {
                        activeVideo.muted = false;
                        activeVideo.currentTime = 0;
                        activeVideo.play().catch(err => console.log("Play error:", err));
                    }
                });
            }
        });
    </script>
</body>
</html>