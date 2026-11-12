@extends('layouts.admin')

@section('title', 'Kelola Iklan TV')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark fs-4">Manajemen Video Iklan / Banner TV</h2>
        <a href="{{ route('iklan') }}" target="_blank" class="btn btn-info btn-sm rounded-pill px-3 fw-semibold text-dark">
            <i class="bi bi-display"></i> Buka Layar TV Iklan
        </a>
    </div>

    <div class="row g-4">
        <!-- Form Upload Video -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3 fs-6">Upload Video Baru</h5>
                <form action="{{ route('admin.iklan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Judul / Keterangan Video</label>
                        <!-- Menggunakan name="judul" sesuai struktur database -->
                        <input type="text" name="judul" class="form-control form-control-sm rounded-3" required placeholder="Contoh: Maklumat Pelayanan">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">File Video (MP4, Max 50MB)</label>
                        <input type="file" name="video" class="form-control form-control-sm rounded-3" accept="video/mp4,video/webm" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill fw-semibold">
                        <i class="bi bi-upload"></i> Unggah Video
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel Daftar Video -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3 fs-6">Daftar Video Tayangan</h5>
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light small">
                            <tr>
                                <th>Judul</th>
                                <th>Preview</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            @forelse($iklanList as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item->judul }}</td>
                                    <td>
                                        <video src="{{ asset($item->file_video) }}" width="120" class="rounded border" muted></video>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.iklan.destroy', $item->id_iklan) }}" method="POST" onsubmit="return confirm('Hapus video ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada video iklan yang diunggah.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection