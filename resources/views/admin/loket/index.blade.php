@extends('layouts.admin')

@section('title', 'Kelola Loket Pelayanan')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold text-dark mb-1">Manajemen Loket Pelayanan</h1>
        <p class="text-muted small mb-0">Kelola loket pelayanan pengadilan dan hubungkan dengan layanan yang tersedia.</p>
    </div>
    <button type="button" class="btn btn-primary btn-sm px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahLoket">
        <i class="bi bi-plus-lg"></i> Tambah Loket Baru
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
                        <th class="py-3">Nama Loket</th>
                        <th class="py-3">Layanan Terikat</th>
                        <th class="py-3">Petugas Penanggung Jawab</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3 text-end pe-4" style="width: 25%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loketList as $index => $loket)
                    <tr>
                        <td class="ps-4 text-muted fw-medium">{{ $index + 1 }}</td>
                        <td class="fw-bold text-dark">{{ $loket->nama_loket }}</td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2.5 py-1 fw-semibold rounded-pill">
                                {{ $loket->layanan->nama_layanan ?? '-' }}
                            </span>
                        </td>
                        <td class="text-secondary small">
                            {{ $loket->petugasLoket->petugas->user->nama ?? 'Belum Ditugaskan' }}
                        </td>
                        <td class="text-center">
                            @if($loket->status_aktif)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 fw-semibold rounded-pill">Aktif</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1 fw-semibold rounded-pill">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <!-- Tombol Toggle Status -->
                                <form method="POST" action="{{ route('admin.loket.toggle', $loket->id_loket) }}" class="m-0 d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm px-2.5 py-1.5 fw-medium {{ $loket->status_aktif ? 'btn-outline-warning text-dark' : 'btn-outline-success' }}">
                                        {{ $loket->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                <!-- Tombol Pemicu Modal Edit -->
                                <button type="button" class="btn btn-outline-primary btn-sm px-2.5 py-1.5 fw-medium" data-bs-toggle="modal" data-bs-target="#modalEditLoket{{ $loket->id_loket }}">
                                    Edit
                                </button>

                                <!-- Tombol Pemicu Modal Hapus -->
                                <button type="button" class="btn btn-outline-danger btn-sm px-2.5 py-1.5 fw-medium" data-bs-toggle="modal" data-bs-target="#modalHapusLoket{{ $loket->id_loket }}">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Edit Loket (Bootstrap Modal) -->
                    <div class="modal fade" id="modalEditLoket{{ $loket->id_loket }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg rounded-4">
                                <div class="modal-header border-bottom px-4 py-3">
                                    <h5 class="modal-title fw-bold text-dark fs-5">Edit Loket</h5>
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.loket.update', $loket->id_loket) }}" class="m-0">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body p-4 text-start">
                                        <div class="mb-3">
                                            <label class="form-label text-xs fw-bold text-secondary text-uppercase">Nama Loket</label>
                                            <input type="text" name="nama_loket" value="{{ $loket->nama_loket }}" required class="form-control bg-light border-0 py-2.5 text-sm shadow-none">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-xs fw-bold text-secondary text-uppercase">Pilih Layanan</label>
                                            <select name="id_layanan" required class="form-select bg-light border-0 py-2.5 text-sm shadow-none">
                                                @foreach($layananList as $layanan)
                                                    <option value="{{ $layanan->id_layanan }}" {{ $loket->id_layanan == $layanan->id_layanan ? 'selected' : '' }}>
                                                        {{ $layanan->nama_layanan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-check pt-2">
                                            <input type="checkbox" name="status_aktif" id="statusEdit{{ $loket->id_loket }}" {{ $loket->status_aktif ? 'checked' : '' }} class="form-check-input">
                                            <label for="statusEdit{{ $loket->id_loket }}" class="form-check-label fw-semibold text-secondary small">Status Aktif</label>
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

                    <!-- Modal Hapus Loket (Bootstrap Modal) -->
                    <div class="modal fade" id="modalHapusLoket{{ $loket->id_loket }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg rounded-4">
                                <div class="modal-body text-center p-4">
                                    <div class="text-danger display-5 mb-3">
                                        <i class="bi bi-exclamation-triangle"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-2">Hapus "{{ $loket->nama_loket }}"?</h5>
                                    <p class="text-muted small mb-4">Tindakan ini tidak dapat dibatalkan dan akan menghapus data loket.</p>
                                    <form method="POST" action="{{ route('admin.loket.destroy', $loket->id_loket) }}" class="m-0">
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
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-display display-6 d-block mb-2 text-secondary opacity-50"></i>
                            Belum ada data loket pelayanan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Loket (Bootstrap Modal) -->
<div class="modal fade" id="modalTambahLoket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-5">Tambah Loket Baru</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.loket.store') }}" class="m-0">
                @csrf
                <div class="modal-body p-4 text-start">
                    <div class="mb-3">
                        <label class="form-label text-xs fw-bold text-secondary text-uppercase">Nama Loket</label>
                        <input type="text" name="nama_loket" placeholder="Contoh: Loket 1" required class="form-control bg-light border-0 py-2.5 text-sm shadow-none">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-xs fw-bold text-secondary text-uppercase">Pilih Layanan</label>
                        <select name="id_layanan" required class="form-select bg-light border-0 py-2.5 text-sm shadow-none">
                            <option value="">-- Pilih Layanan --</option>
                            @foreach($layananList as $layanan)
                                <option value="{{ $layanan->id_layanan }}">{{ $layanan->nama_layanan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-check pt-2">
                        <input type="checkbox" name="status_aktif" id="statusTambah" checked class="form-check-input">
                        <label for="statusTambah" class="form-check-label fw-semibold text-secondary small">Status Aktif</label>
                    </div>
                </div>
                <div class="modal-footer border-top px-4 py-3">
                    <button type="button" class="btn btn-light btn-sm px-4 fw-medium border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-medium">Simpan Loket</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection