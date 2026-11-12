<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Petugas;
use App\Models\PetugasLoket;
use App\Models\Tiket;
use Carbon\Carbon;

class PanggilanController extends Controller
{
    // Helper untuk mendapatkan loket petugas yang sedang login
    private function getLoketPetugas()
    {
        $user = Auth::user();
        $petugas = Petugas::where('user_id', $user->id)->first();
        if (!$petugas) return null;

        $penugasan = PetugasLoket::where('id_petugas', $petugas->id_petugas)->first();
        return $penugasan ? $penugasan->loket : null;
    }

    // 1. Panggil Berikutnya
    public function panggilBerikutnya()
    {
        $loket = $this->getLoketPetugas();
        if (!$loket) {
            return back()->with('error', 'Loket petugas tidak ditemukan.');
        }

        $tanggalHariIni = Carbon::today()->toDateString();

        try {
            DB::transaction(function () use ($loket, $tanggalHariIni) {
                // Selesaikan tiket yang statusnya masih 'dipanggil' (jika ada)
                Tiket::where('id_loket', $loket->id_loket)
                    ->where('tanggal_tiket', $tanggalHariIni)
                    ->where('status', 'dipanggil')
                    ->update(['status' => 'selesai']);

                // Ambil antrean menunggu teratas
                $nextTiket = Tiket::where('id_loket', $loket->id_loket)
                    ->where('tanggal_tiket', $tanggalHariIni)
                    ->where('status', 'menunggu')
                    ->orderBy('id_tiket', 'asc')
                    ->lockForUpdate()
                    ->first();

                if (!$nextTiket) {
                    throw new \Exception('Tidak ada antrean yang menunggu saat ini.');
                }

                // Ubah status menjadi dipanggil & update waktu agar terdeteksi TV Display
                $nextTiket->update([
                    'status' => 'dipanggil',
                    'jam_prakiraan' => Carbon::now()->format('H:i:s'),
                    'updated_at' => Carbon::now() // Memastikan timestamp berubah
                ]);
            });

            return back()->with('success', 'Berhasil memanggil nomor antrean berikutnya.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // 2. Panggil Ulang (Diperbarui dengan touch/updated_at agar suara TV berbunyi)
    // 2. Panggil Ulang
public function panggilUlang($id)
{
    $tiket = Tiket::findOrFail($id);
    
    // Memaksa pembaruan waktu dengan format presisi tinggi agar TV Display langsung merespons
    $tiket->update([
        'status' => 'dipanggil',
        'updated_at' => now()
    ]);

    return back()->with('success', "Memanggil ulang nomor antrean {$tiket->nomor_urut}.");
}

    // 3. Selesaikan Tiket
    public function selesaikan($id)
    {
        $tiket = Tiket::findOrFail($id);
        $tiket->update([
            'status' => 'selesai',
            'updated_at' => Carbon::now()
        ]);

        return back()->with('success', "Antrean {$tiket->nomor_urut} telah diselesaikan.");
    }

    // 4. Batalkan Tiket
    public function batalkan($id)
    {
        $tiket = Tiket::findOrFail($id);
        $tiket->update([
            'status' => 'batal',
            'updated_at' => Carbon::now()
        ]);

        return back()->with('warning', "Antrean {$tiket->nomor_urut} dibatalkan.");
    }
}