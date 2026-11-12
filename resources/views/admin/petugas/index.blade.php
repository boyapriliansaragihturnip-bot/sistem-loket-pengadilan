@extends('layouts.admin')

@section('title', 'Kelola Akun Petugas')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold text-dark mb-1">Manajemen Akun Petugas</h1>
        <p class="text-muted small mb-0">Kelola data petugas loket dan kredensial login mereka.</p>
    </div>
    <button type="button" class="btn btn-primary btn-sm px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPetugas">
        <i class="bi bi-plus-lg"></i> Tambah Petugas Baru
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
                        <th class="py-3">Nama Petugas</th>
                        <th class="py-3">NIP</th>
                        <th class="py-3">Email Login</th>
                        <th class="py-3">Loket Penanggung Jawab</th>
                        <th class="py-3 text-end pe-4" style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($petugasList as $index => $petugas)
                    <tr>
                        <td class="ps-4 text-muted fw-medium">{{ $index + 1 }}</td>
                        <td class="fw-bold text-dark">{{ $petugas->user->nama ?? '-' }}</td>
                        <td class="text-muted font-monospace small">{{ $petugas->nip }}</td>
                        <td class="text-secondary small">{{ $petugas->user->email ?? '-' }}</td>
                        <td>
                            @if($petugas->petugasLoket->isNotEmpty())
                                @foreach($petugas->petugasLoket as $pl)
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2.5 py-1 fw-semibold rounded-pill me-1 mb-1">
                                        {{ $pl->loket->nama_loket ?? '-' }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-muted small fst-italic">Belum ditugaskan</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <!-- Tombol Pemicu Modal Edit -->
                                <button type="button" class="btn btn-outline-primary btn-sm px-2.5 py-1.5 fw-medium" data-bs-toggle="modal" data-bs-target="#modalEditPetugas{{ $petugas->id_petugas }}">
                                    Edit
                                </button>
                                <!-- Tombol Pemicu Modal Hapus -->
                                <button type="button" class="btn btn-outline-danger btn-sm px-2.5 py-1.5 fw-medium" data-bs-toggle="modal" data-bs-target="#modalHapusPetugas{{ $petugas->id_petugas }}">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Edit Petugas (Bootstrap Modal) -->
                    <div class="modal fade" id="modalEditPetugas{{ $petugas->id_petugas }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg rounded-4">
                                <div class="modal-header border-bottom px-4 py-3">
                                    <h5 class="modal-title fw-bold text-dark fs-5">Edit Petugas</h5>
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.petugas.update', $petugas->id_petugas) }}" class="m-0">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body p-4 text-start">
                                        <div class="mb-3">
                                            <label class="form-label text-xs fw-bold text-secondary text-uppercase">Nama Lengkap</label>
                                            <input type="text" name="nama" value="{{ $petugas->user->nama ?? '' }}" required class="form-control bg-light border-0 py-2.5 text-sm shadow-none">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-xs fw-bold text-secondary text-uppercase">NIP</label>
                                            <input type="text" name="nip" value="{{ $petugas->nip }}" maxlength="18" required class="form-control bg-light border-0 py-2.5 text-sm shadow-none font-monospace">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-xs fw-bold text-secondary text-uppercase">Email</label>
                                            <input type="email" name="email" value="{{ $petugas->user->email ?? '' }}" required class="form-control bg-light border-0 py-2.5 text-sm shadow-none">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-xs fw-bold text-secondary text-uppercase">Password Baru (Opsional)</label>
                                            <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="form-control bg-light border-0 py-2.5 text-sm shadow-none">
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top px-4 py-3">
                                        <button type="button" class="btn btn-light btn-sm px-4 fw-medium border" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-medium">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Hapus Petugas (Bootstrap Modal) -->
                    <div class="modal fade" id="modalHapusPetugas{{ $petugas->id_petugas }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg rounded-4">
                                <div class="modal-body text-center p-4">
                                    <div class="text-danger display-5 mb-3">
                                        <i class="bi bi-exclamation-triangle"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-2">Hapus Petugas "{{ $petugas->user->nama ?? '' }}"?</h5>
                                    <p class="text-muted small mb-4">Akun login dan data petugas akan dihapus secara permanen dari sistem.</p>
                                    <form method="POST" action="{{ route('admin.petugas.destroy', $petugas->id_petugas) }}" class="m-0">
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
                            <i class="bi bi-people display-6 d-block mb-2 text-secondary opacity-50"></i>
                            Belum ada data petugas yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Petugas (Bootstrap Modal) -->
<div class="modal fade" id="modalTambahPetugas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-5">Tambah Petugas Baru</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.petugas.store') }}" class="m-0">
                @csrf
                <div class="modal-body p-4 text-start">
                    <div class="mb-3">
                        <label class="form-label text-xs fw-bold text-secondary text-uppercase">Nama Lengkap</label>
                        <input type="text" name="nama" placeholder="Nama lengkap petugas" required class="form-control bg-light border-0 py-2.5 text-sm shadow-none">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-xs fw-bold text-secondary text-uppercase">NIP</label>
                        <input type="text" name="nip" placeholder="18 digit NIP" maxlength="18" required class="form-control bg-light border-0 py-2.5 text-sm shadow-none font-monospace">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-xs fw-bold text-secondary text-uppercase">Email (Untuk Login)</label>
                        <input type="email" name="email" placeholder="petugas@pengadilan.go.id" required class="form-control bg-light border-0 py-2.5 text-sm shadow-none">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-xs fw-bold text-secondary text-uppercase">Password</label>
                        <input type="password" name="password" placeholder="Minimal 6 karakter" required class="form-control bg-light border-0 py-2.5 text-sm shadow-none">
                    </div>
                </div>
                <div class="modal-footer border-top px-4 py-3">
                    <button type="button" class="btn btn-light btn-sm px-4 fw-medium border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-medium">Simpan Petugas</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection