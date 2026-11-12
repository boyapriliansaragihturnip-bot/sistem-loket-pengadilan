@extends('layouts.admin')

@section('title', 'Manajemen Tiket Antrean')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold text-dark mb-1">Manajemen Tiket Antrean</h1>
        <p class="text-muted small mb-0">Pantau, cari, dan kelola seluruh tiket antrean offline dan online.</p>
    </div>
</div>

<!-- Filter Box -->
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
    <form method="GET" action="{{ route('admin.tiket.index') }}" class="row g-3">
        <div class="col-md-2">
            <label class="form-label text-xs fw-bold text-secondary text-uppercase">Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}" class="form-control bg-light border-0 py-2 text-sm shadow-none">
        </div>
        <div class="col-md-3">
            <label class="form-label text-xs fw-bold text-secondary text-uppercase">Loket</label>
            <select name="id_loket" class="form-select bg-light border-0 py-2 text-sm shadow-none">
                <option value="">Semua Loket</option>
                @foreach($loketList as $loket)
                    <option value="{{ $loket->id_loket }}" {{ request('id_loket') == $loket->id_loket ? 'selected' : '' }}>{{ $loket->nama_loket }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label text-xs fw-bold text-secondary text-uppercase">Status</label>
            <select name="status" class="form-select bg-light border-0 py-2 text-sm shadow-none">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="dipanggil" {{ request('status') == 'dipanggil' ? 'selected' : '' }}>Dipanggil</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label text-xs fw-bold text-secondary text-uppercase">Cari Nomor / Booking</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cth: A-001" class="form-control bg-light border-0 py-2 text-sm shadow-none">
        </div>
        <div class="col-md-2 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary btn-sm w-100 py-2 fw-semibold">Filter</button>
            <a href="{{ route('admin.tiket.index') }}" class="btn btn-light btn-sm w-100 py-2 fw-semibold border">Reset</a>
        </div>
    </form>
</div>

<!-- Main Table Card -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small text-uppercase fw-bold">
                    <tr>
                        <th class="py-3 ps-4">No Urut</th>
                        <th class="py-3">Tipe</th>
                        <th class="py-3">Layanan / Loket</th>
                        <th class="py-3">Pengunjung</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3">Waktu</th>
                        <th class="py-3 text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tiketList as $tiket)
                    <tr>
                        <td class="ps-4 fw-black text-dark fs-5">{{ $tiket->nomor_urut }}</td>
                        <td>
                            <span class="badge {{ $tiket->tipe_antrean == 'online' ? 'bg-indigo bg-opacity-10 text-indigo border border-indigo border-opacity-25' : 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25' }} px-3 py-1.5 fw-semibold rounded-pill">
                                {{ ucfirst($tiket->tipe_antrean) }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $tiket->loket->layanan->nama_layanan ?? '-' }}</div>
                            <div class="text-xs text-muted">{{ $tiket->loket->nama_loket ?? '-' }}</div>
                        </td>
                        <td class="text-secondary text-xs">
                            {{ $tiket->pengguna->nama_pengguna ?? 'Pengunjung Offline' }}
                        </td>
                        <td class="text-center">
                            @php
                                $badgeColor = match($tiket->status) {
                                    'menunggu' => 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25',
                                    'dipanggil' => 'bg-info bg-opacity-10 text-info border border-info border-opacity-25 fw-bold',
                                    'selesai' => 'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
                                    'batal' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25',
                                    default => 'bg-secondary bg-opacity-10 text-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeColor }} px-3 py-1.5 fw-semibold rounded-pill">
                                {{ ucfirst($tiket->status) }}
                            </span>
                        </td>
                        <td class="text-xs text-muted">
                            {{ $tiket->tanggal_tiket }} <br> {{ $tiket->jam_prakiraan }}
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <!-- Tombol Pemicu Modal Status -->
                                <button type="button" class="btn btn-outline-primary btn-sm px-2.5 py-1.5 fw-medium" data-bs-toggle="modal" data-bs-target="#modalStatus{{ $tiket->id_tiket }}">
                                    Status
                                </button>
                                <!-- Tombol Pemicu Modal Hapus -->
                                <button type="button" class="btn btn-outline-danger btn-sm px-2.5 py-1.5 fw-medium" data-bs-toggle="modal" data-bs-target="#modalHapusTiket{{ $tiket->id_tiket }}">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Update Status (Bootstrap Modal) -->
                    <div class="modal fade" id="modalStatus{{ $tiket->id_tiket }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg rounded-4">
                                <div class="modal-header border-bottom px-4 py-3">
                                    <h5 class="modal-title fw-bold text-dark fs-5">Ubah Status Tiket {{ $tiket->nomor_urut }}</h5>
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.tiket.status', $tiket->id_tiket) }}" class="m-0">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-body p-4 text-start">
                                        <div class="mb-3">
                                            <label class="form-label text-xs fw-bold text-secondary text-uppercase">Status Baru</label>
                                            <select name="status" class="form-select bg-light border-0 py-2.5 text-sm shadow-none">
                                                <option value="menunggu" {{ $tiket->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                <option value="dipanggil" {{ $tiket->status == 'dipanggil' ? 'selected' : '' }}>Dipanggil</option>
                                                <option value="selesai" {{ $tiket->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                <option value="batal" {{ $tiket->status == 'batal' ? 'selected' : '' }}>Batal</option>
                                            </select>
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

                    <!-- Modal Hapus Tiket (Bootstrap Modal) -->
                    <div class="modal fade" id="modalHapusTiket{{ $tiket->id_tiket }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg rounded-4">
                                <div class="modal-body text-center p-4">
                                    <div class="text-danger display-5 mb-3">
                                        <i class="bi bi-exclamation-triangle"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-2">Hapus Tiket {{ $tiket->nomor_urut }}?</h5>
                                    <p class="text-muted small mb-4">Data antrean akan dihapus secara permanen dari sistem.</p>
                                    <form method="POST" action="{{ route('admin.tiket.destroy', $tiket->id_tiket) }}" class="m-0">
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
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-ticket-detailed display-6 d-block mb-2 text-secondary opacity-50"></i>
                            Tidak ada data tiket antrean yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-top py-3 px-4">
        {{ $tiketList->withQueryString()->links() }}
    </div>
</div>
@endsection