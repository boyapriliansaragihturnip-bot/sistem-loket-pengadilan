@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold text-dark text-uppercase tracking-tight fs-4 mb-1">Pengaturan Sistem</h2>
    <p class="text-muted small mb-0">Kelola identitas instansi dan konfigurasi umum sistem antrean pengadilan.</p>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 max-w-lg bg-white" style="max-width: 700px;">
    <div class="card-body p-0">
        <form method="POST" action="{{ route('admin.pengaturan.update') }}">
            @csrf
            @method('PUT')
            
            <div class="vstack gap-3">
                <div>
                    <label class="form-label text-uppercase fs-7 fw-bold text-secondary">Nama Pengadilan</label>
                    <input type="text" name="nama_pengadilan" value="{{ $settings['nama_pengadilan'] ?? 'Pengadilan Negeri Kelas IA' }}" required class="form-control rounded-3 bg-light border-0 py-2.5">
                </div>

                <div>
                    <label class="form-label text-uppercase fs-7 fw-bold text-secondary">Alamat Lengkap</label>
                    <textarea name="alamat_pengadilan" rows="3" required class="form-control rounded-3 bg-light border-0 py-2.5">{{ $settings['alamat_pengadilan'] ?? '' }}</textarea>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-uppercase fs-7 fw-bold text-secondary">Nomor Telepon</label>
                        <input type="text" name="telepon_pengadilan" value="{{ $settings['telepon_pengadilan'] ?? '' }}" class="form-control rounded-3 bg-light border-0 py-2.5">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-uppercase fs-7 fw-bold text-secondary">Ketua / Pimpinan</label>
                        <input type="text" name="pimpinan_pengadilan" value="{{ $settings['pimpinan_pengadilan'] ?? '' }}" class="form-control rounded-3 bg-light border-0 py-2.5">
                    </div>
                </div>

                <div class="pt-3 border-top d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary rounded-3 px-4 py-2.5 fw-semibold shadow-sm">Simpan Pengaturan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection