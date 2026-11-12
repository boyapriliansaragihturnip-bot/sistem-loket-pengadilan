<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\LoketController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\PenugasanController;
use App\Http\Controllers\Admin\TiketController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\IklanController; 
use App\Http\Controllers\Petugas\PetugasDashboardController;
use App\Http\Controllers\Petugas\PanggilanController;
use App\Http\Controllers\Public\DisplayController;
use App\Models\Iklan;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Informasi Antrian Pengadilan (LAN Offline)
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. PUBLIC ROUTES (Anjungan & Pengunjung)
// ==========================================
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::post('/queue/take', [PublicController::class, 'ambilAntreanOffline'])->name('queue.take');
Route::get('/queue/ticket/{token}', [PublicController::class, 'showTicket'])->name('queue.ticket');

// ==========================================
// 2. AUTHENTICATION ROUTES
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==========================================
// 3. TV DISPLAY & API POLLING LAN (Real-Time)
// ==========================================
Route::get('/display', [DisplayController::class, 'index'])->name('display');
Route::get('/api/display/data', [DisplayController::class, 'fetchData'])->name('api.display.data');

// Rute Layar Iklan TV Publik (Menampilkan semua video yang diunggah admin secara looping full screen)
Route::get('/iklan', function () {
    $activeIklan = Iklan::latest()->get();
    return view('public.iklan', compact('activeIklan'));
})->name('iklan');

// ==========================================
// 4. PROTECTED ROUTES - ADMIN
// ==========================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin (Statistik Utama)
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD Layanan Pengadilan
    Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
    Route::post('/layanan', [LayananController::class, 'store'])->name('layanan.store');
    Route::put('/layanan/{id}', [LayananController::class, 'update'])->name('layanan.update');
    Route::patch('/layanan/{id}/toggle', [LayananController::class, 'toggleStatus'])->name('layanan.toggle');
    Route::delete('/layanan/{id}', [LayananController::class, 'destroy'])->name('layanan.destroy');

    // CRUD Loket Pelayanan
    Route::get('/loket', [LoketController::class, 'index'])->name('loket.index');
    Route::post('/loket', [LoketController::class, 'store'])->name('loket.store');
    Route::put('/loket/{id}', [LoketController::class, 'update'])->name('loket.update');
    Route::patch('/loket/{id}/toggle', [LoketController::class, 'toggleStatus'])->name('loket.toggle');
    Route::delete('/loket/{id}', [LoketController::class, 'destroy'])->name('loket.destroy');

    // CRUD Data Petugas
    Route::get('/petugas', [PetugasController::class, 'index'])->name('petugas.index');
    Route::post('/petugas', [PetugasController::class, 'store'])->name('petugas.store');
    Route::put('/petugas/{id}', [PetugasController::class, 'update'])->name('petugas.update');
    Route::delete('/petugas/{id}', [PetugasController::class, 'destroy'])->name('petugas.destroy');

    // Penugasan Petugas ke Loket
    Route::get('/penugasan', [PenugasanController::class, 'index'])->name('penugasan.index');
    Route::post('/penugasan', [PenugasanController::class, 'store'])->name('penugasan.store');
    Route::delete('/penugasan/{id}', [PenugasanController::class, 'destroy'])->name('penugasan.destroy');

    // CRUD Tiket Antrean (Admin Monitoring & Correction)
    Route::get('/tiket', [TiketController::class, 'index'])->name('tiket.index');
    Route::patch('/tiket/{id}/status', [TiketController::class, 'updateStatus'])->name('tiket.status');
    Route::delete('/tiket/{id}', [TiketController::class, 'destroy'])->name('tiket.destroy');

    // Laporan & Rekapitulasi Antrean
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Pengaturan Sistem & Identitas Instansi
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

    // Manajemen Video Iklan TV oleh Admin
    Route::get('/iklan', [IklanController::class, 'index'])->name('iklan.index');
    Route::post('/iklan', [IklanController::class, 'store'])->name('iklan.store');
    Route::delete('/iklan/{id}', [IklanController::class, 'destroy'])->name('iklan.destroy');
});

// ==========================================
// 5. PROTECTED ROUTES - PETUGAS LOKET
// ==========================================
Route::middleware(['auth', 'petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
    
    // Rute Laporan Kinerja Petugas
    Route::get('/laporan', [PetugasDashboardController::class, 'laporan'])->name('laporan');

    // Aksi Pemanggilan Antrean (Panggil, Ulang, Selesai, Batal)
    Route::post('/panggil/berikutnya', [PanggilanController::class, 'panggilBerikutnya'])->name('panggil.berikutnya');
    Route::post('/panggil/ulang/{id}', [PanggilanController::class, 'panggilUlang'])->name('panggil.ulang');
    Route::post('/panggil/selesai/{id}', [PanggilanController::class, 'selesaikan'])->name('panggil.selesai');
    Route::post('/panggil/batal/{id}', [PanggilanController::class, 'batalkan'])->name('panggil.batal');
});

// ==========================================
// 6. PROTECTED ROUTES - PENGGUNA (Opsional)
// ==========================================
Route::middleware(['auth', 'pengguna'])->prefix('pengguna')->name('pengguna.')->group(function () {
    Route::get('/dashboard', function () {
        return view('pengguna.dashboard');
    })->name('dashboard');
});