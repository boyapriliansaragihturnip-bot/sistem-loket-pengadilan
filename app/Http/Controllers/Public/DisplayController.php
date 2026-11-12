<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loket;
use App\Models\Tiket;
use App\Models\Pengaturan;
use Carbon\Carbon;

class DisplayController extends Controller
{
    // Tampilkan Halaman Utama TV Display
    public function index()
    {
        $tanggalHariIni = Carbon::today()->toDateString();
        $namaPengadilan = Pengaturan::where('setting_key', 'nama_pengadilan')->value('setting_value') ?? 'Pengadilan Negeri';

        // Ambil semua loket aktif beserta tiket yang sedang dipanggil
        $loketList = Loket::with(['layanan', 'petugasLoket.petugas.user'])
            ->where('status_aktif', true)
            ->get();

        // Cari tiket yang sedang aktif dipanggil saat ini
        $tiketDipanggil = Tiket::with(['loket.layanan'])
            ->where('tanggal_tiket', $tanggalHariIni)
            ->where('status', 'dipanggil')
            ->orderBy('updated_at', 'desc')
            ->first();

        return view('public.display', compact('namaPengadilan', 'loketList', 'tiketDipanggil'));
    }

    // API JSON untuk Polling Data Real-Time di LAN (Auto Refresh tanpa reload page)
  public function fetchData()
{
    $tanggalHariIni = Carbon::today()->toDateString();

    $loketList = Loket::with(['layanan', 'petugasLoket.petugas.user'])
        ->where('status_aktif', true)
        ->get()
        ->map(function($loket) use ($tanggalHariIni) {
            $aktif = Tiket::where('id_loket', $loket->id_loket)
                ->where('tanggal_tiket', $tanggalHariIni)
                ->where('status', 'dipanggil')
                ->first();

            return [
                'id_loket' => $loket->id_loket,
                'nama_loket' => $loket->nama_loket,
                'nama_layanan' => $loket->layanan->nama_layanan ?? '-',
                'nomor_aktif' => $aktif ? $aktif->nomor_urut : '---',
                'id_tiket_aktif' => $aktif ? $aktif->id_tiket : null,
            ];
        });

    $latestDipanggil = Tiket::with(['loket'])
        ->where('tanggal_tiket', $tanggalHariIni)
        ->where('status', 'dipanggil')
        ->latest('updated_at')
        ->first();

    return response()->json([
        'loket_list' => $loketList,
        'latest_dipanggil' => $latestDipanggil ? [
            'id_tiket' => $latestDipanggil->id_tiket,
            'nomor_urut' => $latestDipanggil->nomor_urut,
            'nama_loket' => $latestDipanggil->loket->nama_loket ?? 'Loket',
            // Menggabungkan updated_at dengan fungsi acak agar panggil ulang berulang kali tetap terbaca sebagai data baru
            'waktu_update' => $latestDipanggil->updated_at->format('Y-m-d H:i:s.u') . '-' . rand(1000, 9999)
        ] : null
    ]);
}
}