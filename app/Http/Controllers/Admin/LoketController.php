<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loket;
use App\Models\Layanan;

class LoketController extends Controller
{
    public function index()
    {
        $loketList = Loket::with(['layanan', 'petugasLoket.petugas.user'])->orderBy('id_loket', 'asc')->get();
        $layananList = Layanan::where('status_aktif', true)->get();
        
        return view('admin.loket.index', compact('loketList', 'layananList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_loket' => 'required|string|max:50',
            'id_layanan' => 'required|exists:layanan,id_layanan',
        ], [
            'nama_loket.required' => 'Nama loket wajib diisi.',
            'id_layanan.required' => 'Layanan tujuan wajib dipilih.',
            'id_layanan.exists' => 'Layanan yang dipilih tidak valid.',
        ]);

        Loket::create([
            'nama_loket' => $request->nama_loket,
            'id_layanan' => $request->id_layanan,
            'status_aktif' => $request->has('status_aktif') ? true : false,
        ]);

        return redirect()->route('admin.loket.index')->with('success', 'Loket pelayanan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $loket = Loket::findOrFail($id);

        $request->validate([
            'nama_loket' => 'required|string|max:50',
            'id_layanan' => 'required|exists:layanan,id_layanan',
        ], [
            'nama_loket.required' => 'Nama loket wajib diisi.',
            'id_layanan.required' => 'Layanan wajib dipilih.',
        ]);

        $loket->update([
            'nama_loket' => $request->nama_loket,
            'id_layanan' => $request->id_layanan,
            'status_aktif' => $request->has('status_aktif') ? true : false,
        ]);

        return redirect()->route('admin.loket.index')->with('success', 'Data loket berhasil diperbarui.');
    }

    public function toggleStatus($id)
    {
        $loket = Loket::findOrFail($id);
        $loket->status_aktif = !$loket->status_aktif;
        $loket->save();

        $statusText = $loket->status_aktif ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.loket.index')->with('success', "Status {$loket->nama_loket} berhasil {$statusText}.");
    }

    public function destroy($id)
    {
        $loket = Loket::withCount(['tiket', 'petugasLoket'])->findOrFail($id);

        if ($loket->tiket_count > 0 || $loket->petugas_loket_count > 0) {
            return redirect()->route('admin.loket.index')->with('error', 'Loket tidak dapat dihapus karena memiliki riwayat tiket atau penugasan aktif. Nonaktifkan loket sebagai alternatif.');
        }

        $loket->delete();
        return redirect()->route('admin.loket.index')->with('success', 'Loket berhasil dihapus dari sistem.');
    }
}