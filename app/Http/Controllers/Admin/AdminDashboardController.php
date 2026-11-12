<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Loket;
use App\Models\Petugas;
use App\Models\Tiket;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $tanggalHariIni = Carbon::today()->toDateString();

        // Mengambil data statistik ringkasan
        $totalLayanan = Layanan::count();
        $totalLoket = Loket::count();
        $totalPetugas = Petugas::count();
        
        // Statistik Tiket Hari Ini
        $tiketHariIni = Tiket::whereDate('tanggal_tiket', $tanggalHariIni);
        $totalTiketHariIni = (clone $tiketHariIni)->count();
        $tiketMenunggu = (clone $tiketHariIni)->where('status', 'menunggu')->count();
        $tiketDipanggil = (clone $tiketHariIni)->where('status', 'dipanggil')->count();
        $tiketSelesai = (clone $tiketHariIni)->where('status', 'selesai')->count();

        return view('admin.dashboard', compact(
            'totalLayanan',
            'totalLoket',
            'totalPetugas',
            'totalTiketHariIni',
            'tiketMenunggu',
            'tiketDipanggil',
            'tiketSelesai'
        ));
    }
}