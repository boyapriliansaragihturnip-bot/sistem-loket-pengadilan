<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tiket;
use App\Models\Loket;
use Carbon\Carbon;

class LaporanController extends Controller
{
    // Tampilkan Halaman Rekapitulasi & Filter Laporan
    public function index(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::today()->startOfMonth()->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', Carbon::today()->toDateString());
        $idLoket = $request->input('id_loket');

        $query = Tiket::with(['loket.layanan', 'pengguna'])
            ->whereBetween('tanggal_tiket', [$tanggalMulai, $tanggalSelesai]);

        if ($idLoket) {
            $query->where('id_loket', $idLoket);
        }

        $laporanList = $query->orderBy('tanggal_tiket', 'desc')->orderBy('id_tiket', 'desc')->get();
        $loketList = Loket::where('status_aktif', true)->get();

        // Statistik Ringkasan
        $totalTiket = $laporanList->count();
        $totalSelesai = $laporanList->where('status', 'selesai')->count();
        $totalBatal = $laporanList->where('status', 'batal')->count();
        $totalMenunggu = $laporanList->where('status', 'menunggu')->count();

        return view('admin.laporan.index', compact(
            'laporanList', 'loketList', 'tanggalMulai', 'tanggalSelesai', 'idLoket',
            'totalTiket', 'totalSelesai', 'totalBatal', 'totalMenunggu'
        ));
    }
}