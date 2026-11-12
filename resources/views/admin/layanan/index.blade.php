@extends('layouts.admin')

@section('title', 'Kelola Layanan Pengadilan')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold text-dark mb-1">Manajemen Layanan Pengadilan</h1>
        <p class="text-muted small mb-0">Kelola jenis layanan publik yang tersedia untuk diambil nomor antreannya.</p>
    </div>
    <button type="button" class="btn btn-primary btn-sm px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahLayanan">
        <i class="bi bi-plus-lg"></i> Tambah Layanan Baru
    </button>
</div>

<!-- Main Table Card -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small text-uppercase fw-bold">
                    <tr>
                        <th class="py-3 ps-4" style="width: 5%;">No</th>
                        <th class="py-3" style="width: 25%;">Nama Layanan</th>
                        <th class="py-3">Deskripsi</th>
                        <th class="py-3 text-center" style="width: 15%;">Status</th>
                        <th class="py-3 text-end pe-4" style="width: 25%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($layananList as $index => $layanan)
                    <tr>
                        <td class="ps-4 text-muted fw-medium">{{ $index + 1 }}</td>
                        <td class="fw-bold text-dark">{{ $layanan->nama_layanan }}</td>
                        <td class="text-secondary small">{{ $layanan->deskripsi ?? '-' }}</td>
                        <td class="text-center">
                            @if($layanan->status_aktif)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 fw-semibold rounded-pill">Aktif</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1 fw-semibold rounded-pill">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <!-- Tombol Toggle Status -->
                                <form method="POST" action="{{ route('admin.layanan.toggle', $layanan->id_layanan) }}" class="m-0 d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm px-2.5 py-1.5 fw-medium {{ $layanan->status_aktif ? 'btn-outline-warning text-dark' : 'btn-outline-success' }}">
                                        {{ $layanan->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                <!-- Tombol Pemicu Modal Edit -->
                                <button type="button" class="btn btn-outline-primary btn-sm px-2.5 py-1.5 fw-medium" data-bs-toggle="modal" data-bs-target="#modalEditLayanan{{ $layanan->id_layanan }}">
                                    Edit
                                </button>

                                <!-- Tombol Pemicu Modal Hapus -->
                                <button type="button" class="btn btn-outline-danger btn-sm px-2.5 py-1.5 fw-medium" data-bs-toggle="modal" data-bs-target="#modalHapusLayanan{{ $layanan->id_layanan }}">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-folder2-open display-6 d-block mb-2 text-secondary opacity-50"></i>
                            Belum ada data layanan pengadilan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- KUMPULAN MODAL DI LUAR TABEL (AGAR AMAN) -->
<!-- ========================================== -->
@foreach($layananList as $layanan)
    <!-- Modal Edit Layanan -->
    <div class="modal fade" id="modalEditLayanan{{ $layanan->id_layanan }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom px-4 py-3">
                    <h5 class="modal-title fw-bold text-dark fs-5">Edit Layanan</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.layanan.update', $layanan->id_layanan) }}" class="m-0">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4 text-start">
                        <div class="mb-3">
                            <label class="form-label text-xs fw-bold text-secondary text-uppercase">Nama Layanan</label>
                            <input type="text" name="nama_layanan" value="{{ $layanan->nama_layanan }}" required class="form-control bg-light border-0 py-2.5 text-sm shadow-none">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-xs fw-bold text-secondary text-uppercase">Deskripsi</label>
                            <textarea name="deskripsi" rows="3" class="form-control bg-light border-0 py-2.5 text-sm shadow-none">{{ $layanan->deskripsi }}</textarea>
                        </div>
                        <div class="form-check pt-2">
                            <input type="checkbox" name="status_aktif" id="statusEdit{{ $layanan->id_layanan }}" {{ $layanan->status_aktif ? 'checked' : '' }} class="form-check-input">
                            <label for="statusEdit{{ $layanan->id_layanan }}" class="form-check-label fw-semibold text-secondary small">Status Aktif</label>
                        </div>
                    </div>
                    <div class="modal-footer border-top px-4 py-3">
                        <button type="button" class="btn btn-light btn-sm px-4 fw-medium border" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-medium">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Layanan -->
    <div class="modal fade" id="modalHapusLayanan{{ $layanan->id_layanan }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-body text-center p-4">
                    <div class="text-danger display-5 mb-3">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Hapus Layanan "{{ $layanan->nama_layanan }}"?</h5>
                    <p class="text-muted small mb-4">Tindakan ini tidak dapat dibatalkan dan akan menghapus data layanan.</p>
                    <form method="POST" action="{{ route('admin.layanan.destroy', $layanan->id_layanan) }}" class="m-0">
                        @csrf
                        @method('DELETE')
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-light btn-sm px-4 fw-medium border" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger btn-sm px-4 fw-medium">Ya, Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- Modal Tambah Layanan (Bootstrap Modal) -->
<div class="modal fade" id="modalTambahLayanan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-5">Tambah Layanan Baru</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.layanan.store') }}" class="m-0">
                @csrf
                <div class="modal-body p-4 text-start">
                    <div class="mb-3">
                        <label class="form-label text-xs fw-bold text-secondary text-uppercase">Nama Layanan</label>
                        <input type="text" name="nama_layanan" placeholder="Contoh: Perdata" required class="form-control bg-light border-0 py-2.5 text-sm shadow-none">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-xs fw-bold text-secondary text-uppercase">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" placeholder="Keterangan singkat layanan..." class="form-control bg-light border-0 py-2.5 text-sm shadow-none"></textarea>
                    </div>
                    <div class="form-check pt-2">
                        <input type="checkbox" name="status_aktif" id="statusTambah" checked class="form-check-input">
                        <label for="statusTambah" class="form-check-label fw-semibold text-secondary small">Status Aktif</label>
                    </div>
                </div>
                <div class="modal-footer border-top px-4 py-3">
                    <button type="button" class="btn btn-light btn-sm px-4 fw-medium border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-medium">Simpan Layanan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection