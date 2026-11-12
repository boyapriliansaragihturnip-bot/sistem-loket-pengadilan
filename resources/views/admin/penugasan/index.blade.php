@extends('layouts.admin')

@section('title', 'Penugasan Petugas Loket')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold text-dark mb-1">Manajemen Penugasan Loket</h1>
        <p class="text-muted small mb-0">Atur penanggung jawab petugas untuk masing-masing loket pelayanan pengadilan.</p>
    </div>
    <button type="button" class="btn btn-primary btn-sm px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPenugasan">
        <i class="bi bi-plus-lg"></i> Atur Penugasan Baru
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
                        <th class="py-3">Layanan</th>
                        <th class="py-3">Petugas Penanggung Jawab</th>
                        <th class="py-3">NIP Petugas</th>
                        <th class="py-3 text-end pe-4" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penugasanList as $index => $item)
                    <tr>
                        <td class="ps-4 text-muted fw-medium">{{ $index + 1 }}</td>
                        <td class="fw-bold text-dark">{{ $item->loket->nama_loket ?? '-' }}</td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-1.5 fw-semibold rounded-pill">
                                {{ $item->loket->layanan->nama_layanan ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <i class="bi bi-person small"></i>
                                </div>
                                <span class="fw-semibold text-secondary">{{ $item->petugas->user->nama ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="text-muted font-monospace small">{{ $item->petugas->nip ?? '-' }}</td>
                        <td class="text-end pe-4">
                            <!-- Tombol Pemicu Modal Hapus -->
                            <button type="button" class="btn btn-outline-danger btn-sm px-3 fw-medium d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalHapusPenugasan{{ $item->id_petugas_loket }}">
                                <i class="bi bi-x-circle"></i> Batalkan
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Hapus Penugasan (Bootstrap Modal) -->
                    <div class="modal fade" id="modalHapusPenugasan{{ $item->id_petugas_loket }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg rounded-4">
                                <div class="modal-body text-center p-4">
                                    <div class="text-warning display-5 mb-3">
                                        <i class="bi bi-exclamation-triangle"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-2">Lepas Penugasan Loket Ini?</h5>
                                    <p class="text-muted small mb-4">Petugas <strong>{{ $item->petugas->user->nama ?? '' }}</strong> tidak lagi bertanggung jawab atas loket ini.</p>
                                    <form method="POST" action="{{ route('admin.penugasan.destroy', $item->id_petugas_loket) }}" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-light btn-sm px-4 fw-medium border" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger btn-sm px-4 fw-medium">Ya, Lepas</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-folder2-open display-6 d-block mb-2 text-secondary opacity-50"></i>
                            Belum ada penugasan petugas ke loket saat ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah / Atur Penugasan (Bootstrap Modal) -->
<div class="modal fade" id="modalTambahPenugasan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-5">Atur Penugasan Petugas</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.penugasan.store') }}" class="m-0">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-xs fw-bold text-secondary text-uppercase">Pilih Loket</label>
                        <select name="id_loket" required class="form-select bg-light border-0 py-2.5 text-sm shadow-none">
                            <option value="">-- Pilih Loket Pelayanan --</option>
                            @foreach($loketList as $loket)
                                <option value="{{ $loket->id_loket }}">{{ $loket->nama_loket }} ({{ $loket->layanan->nama_layanan ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-xs fw-bold text-secondary text-uppercase">Pilih Petugas</label>
                        <select name="id_petugas" required class="form-select bg-light border-0 py-2.5 text-sm shadow-none">
                            <option value="">-- Pilih Petugas --</option>
                            @foreach($petugasList as $petugas)
                                <option value="{{ $petugas->id_petugas }}">{{ $petugas->user->nama ?? '-' }} (NIP: {{ $petugas->nip }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top px-4 py-3">
                    <button type="button" class="btn btn-light btn-sm px-4 fw-medium border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-medium">Simpan Penugasan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection