<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tiket;
use App\Models\Loket;
use Carbon\Carbon;

class TiketController extends Controller
{
    // Tampilkan Daftar Tiket dengan Filter
    public function index(Request $request)
    {
        $query = Tiket::with(['loket.layanan', 'pengguna']);

        // Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_tiket', $request->tanggal);
        } else {
            $query->whereDate('tanggal_tiket', Carbon::today());
        }

        // Filter Loket
        if ($request->filled('id_loket')) {
            $query->where('id_loket', $request->id_loket);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Tipe Antrean
        if ($request->filled('tipe_antrean')) {
            $query->where('tipe_antrean', $request->tipe_antrean);
        }

        // Pencarian Nomor / Kode Booking
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_urut', 'like', "%{$search}%")
                  ->orWhere('kode_booking', 'like', "%{$search}%");
            });
        }

        $tiketList = $query->orderBy('id_tiket', 'desc')->paginate(15);
        $loketList = Loket::where('status_aktif', true)->get();

        return view('admin.tiket.index', compact('tiketList', 'loketList'));
    }

    // Ubah Status Tiket (Manual Admin Correction)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,dipanggil,selesai,batal',
        ]);

        $tiket = Tiket::findOrFail($id);
        $tiket->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.tiket.index')->with('success', "Status tiket {$tiket->nomor_urut} berhasil diperbarui menjadi {$request->status}.");
    }

    // Hapus Tiket
    public function destroy($id)
    {
        $tiket = Tiket::findOrFail($id);
        $tiket->delete();

        return redirect()->route('admin.tiket.index')->with('success', 'Data tiket berhasil dihapus.');
    }
}