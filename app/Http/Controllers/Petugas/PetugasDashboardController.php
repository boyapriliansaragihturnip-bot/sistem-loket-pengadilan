<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Petugas;
use App\Models\PetugasLoket;
use App\Models\Tiket;
use Carbon\Carbon;

class PetugasDashboardController extends Controller
{
    // Tampilkan Dashboard Petugas & Status Loket Tugasnya
    public function index()
    {
        $user = Auth::user();
        $petugas = Petugas::where('user_id', $user->id)->first();

        if (!$petugas) {
            return view('petugas.dashboard', [
                'error' => 'Akun Anda belum terdaftar sebagai data petugas aktif.'
            ]);
        }

        // Ambil loket yang ditugaskan ke petugas ini
        $penugasan = PetugasLoket::with(['loket.layanan'])->where('id_petugas', $petugas->id_petugas)->first();

        if (!$penugasan || !$penugasan->loket) {
            return view('petugas.dashboard', [
                'error' => 'Anda belum ditugaskan ke loket pelayanan manapun oleh Admin.'
            ]);
        }

        $loket = $penugasan->loket;
        $tanggalHariIni = Carbon::today()->toDateString();

        // Tiket yang sedang dipanggil / aktif
        $tiketAktif = Tiket::where('id_loket', $loket->id_loket)
            ->where('tanggal_tiket', $tanggalHariIni)
            ->where('status', 'dipanggil')
            ->first();

        // Daftar antrean yang masih menunggu di loket ini
        $antreanMenunggu = Tiket::where('id_loket', $loket->id_loket)
            ->where('tanggal_tiket', $tanggalHariIni)
            ->where('status', 'menunggu')
            ->orderBy('id_tiket', 'asc')
            ->get();

        // Statistik harian loket
        $totalSelesai = Tiket::where('id_loket', $loket->id_loket)
            ->where('tanggal_tiket', $tanggalHariIni)
            ->where('status', 'selesai')
            ->count();

        return view('petugas.dashboard', compact('user', 'loket', 'tiketAktif', 'antreanMenunggu', 'totalSelesai'));
    }

    // Tampilkan Halaman Laporan Kinerja Petugas
    public function laporan(Request $request)
    {
        $user = Auth::user();
        $petugas = Petugas::where('user_id', $user->id)->first();

        if (!$petugas) {
            return redirect()->route('petugas.dashboard')->with('error', 'Data petugas tidak ditemukan.');
        }

        $penugasan = PetugasLoket::where('id_petugas', $petugas->id_petugas)->first();
        $idLoket = $penugasan ? $penugasan->id_loket : null;

        // Ambil filter tanggal dari request, default hari ini jika kosong
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::today()->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', Carbon::today()->toDateString());

        // Ambil data tiket berdasarkan loket petugas dan rentang tanggal
        $query = Tiket::with(['loket', 'layanan'])
            ->whereDate('created_at', '>=', $tanggalMulai)
            ->whereDate('created_at', '<=', $tanggalSelesai);

        if ($idLoket) {
            $query->where('id_loket', $idLoket);
        }

        $laporanTiket = $query->orderBy('updated_at', 'desc')->get();

        // Hitung statistik ringkas laporan
        $totalDilayani = $laporanTiket->where('status', 'selesai')->count();
        $totalSemua = $laporanTiket->count();

        return view('petugas.laporan', compact('laporanTiket', 'tanggalMulai', 'tanggalSelesai', 'totalDilayani', 'totalSemua'));
    }
}