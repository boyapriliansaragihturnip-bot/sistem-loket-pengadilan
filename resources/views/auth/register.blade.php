<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pengguna - Sistem Informasi Antrian Pengadilan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center py-5" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-primary">REGISTRASI PENGGUNA</h3>
                            <p class="text-muted small">Buat akun untuk pendaftaran antrean online pengadilan</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register.process') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Nama lengkap sesuai identitas">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Alamat Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required placeholder="nama@contoh.com">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nik" class="form-label fw-semibold">NIK (Opsional)</label>
                                    <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik') }}" maxlength="16" placeholder="16 digit NIK">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="no_hp" class="form-label fw-semibold">Nomor HP / WhatsApp</label>
                                    <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required placeholder="Minimal 6 karakter">
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password">
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg fw-semibold">Daftar Sekarang</button>
                            </div>

                            <div class="text-center">
                                <p class="text-muted small">Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Login di sini</a></p>
                                <a href="/" class="text-decoration-none small text-secondary">← Kembali ke Beranda Utama</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>