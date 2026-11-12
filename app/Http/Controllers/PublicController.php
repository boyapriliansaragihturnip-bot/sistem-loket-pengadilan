<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Layanan;
use App\Models\Loket;
use App\Models\Tiket;
use App\Models\Pengaturan;
use Carbon\Carbon;

class PublicController
{
    // Tampilkan Halaman Utama Publik / Pilih Layanan
    public function index()
    {
        $layananList = Layanan::where('status_aktif', true)
            ->with(['loket' => function($q) {
                $q->where('status_aktif', true);
            }])->get();

        $namaPengadilan = Pengaturan::where('setting_key', 'nama_pengadilan')->value('setting_value') ?? 'Pengadilan Negeri';

        return view('welcome', compact('layananList', 'namaPengadilan'));
    }

    // Proses Pengambilan Antrean Offline (Tanpa Login)
    public function ambilAntreanOffline(Request $request)
    {
        $request->validate([
            'id_layanan' => 'required|exists:layanan,id_layanan',
        ]);

        $tanggalHariIni = Carbon::today()->toDateString();

        try {
            $tiket = DB::transaction(function () use ($request, $tanggalHariIni) {
                // 1. Cari loket aktif untuk layanan ini
                $loket = Loket::where('id_layanan', $request->id_layanan)
                    ->where('status_aktif', true)
                    ->first();

                if (!$loket) {
                    throw new \Exception('Maaf, belum ada loket aktif yang melayani layanan ini.');
                }

                // 2. Hitung nomor urut terakhir pada loket & tanggal yang sama
                $lastTiket = Tiket::where('id_loket', $loket->id_loket)
                    ->where('tanggal_tiket', $tanggalHariIni)
                    ->lockForUpdate()
                    ->orderBy('id_tiket', 'desc')
                    ->first();

                $nextNumber = $lastTiket ? ((int) str_replace(['A-', 'B-', 'C-', 'D-', 'OFF-'], '', $lastTiket->nomor_urut)) + 1 : 1;
                
                // Awalan nomor berdasarkan ID Layanan
                $prefix = match((int)$request->id_layanan) {
                    1 => 'A-',
                    2 => 'B-',
                    3 => 'C-',
                    4 => 'D-',
                    default => 'A-',
                };

                $nomorUrutFormated = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                $tokenTiket = Str::random(32);

                // 3. Simpan tiket offline (id_pengguna NULL)
                return Tiket::create([
                    'id_loket' => $loket->id_loket,
                    'id_pengguna' => null,
                    'nomor_urut' => $nomorUrutFormated,
                    'kode_booking' => null,
                    'tipe_antrean' => 'offline',
                    'tanggal_tiket' => $tanggalHariIni,
                    'jam_prakiraan' => Carbon::now()->format('H:i:s'),
                    'status' => 'menunggu',
                ]);
            });

            return redirect()->route('queue.ticket', ['token' => $tiket->id_tiket])
                ->with('success', 'Nomor antrean Anda berhasil dibuat.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Tampilkan Halaman Tiket Antrean (Placeholder publik sebelum Tahap 11/21)
    public function showTicket($token)
    {
        $tiket = Tiket::with(['loket.layanan'])->findOrFail($token);
        $namaPengadilan = Pengaturan::where('setting_key', 'nama_pengadilan')->value('setting_value') ?? 'Pengadilan Negeri';

        return view('queue.ticket', compact('tiket', 'namaPengadilan'));
    }
}