<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PetugasLoket;
use App\Models\Loket;
use App\Models\Petugas;

class PenugasanController extends Controller
{
    // Tampilkan Daftar Penugasan Loket
    public function index()
    {
        $penugasanList = PetugasLoket::with(['loket.layanan', 'petugas.user'])->get();
        $loketList = Loket::with('layanan')->where('status_aktif', true)->get();
        $petugasList = Petugas::with('user')->get();

        return view('admin.penugasan.index', compact('penugasanList', 'loketList', 'petugasList'));
    }

    // Simpan atau Perbarui Penugasan Petugas ke Loket
    public function store(Request $request)
    {
        $request->validate([
            'id_loket' => 'required|exists:loket,id_loket',
            'id_petugas' => 'required|exists:petugas,id_petugas',
        ], [
            'id_loket.required' => 'Loket wajib dipilih.',
            'id_petugas.required' => 'Petugas wajib dipilih.',
        ]);

        // Simpan atau update penugasan (1 loket 1 penanggung jawab aktif)
        PetugasLoket::updateOrCreate(
            ['id_loket' => $request->id_loket],
            ['id_petugas' => $request->id_petugas]
        );

        return redirect()->route('admin.penugasan.index')->with('success', 'Penugasan petugas ke loket berhasil disimpan.');
    }

    // Hapus Penugasan
    public function destroy($id)
    {
        $penugasan = PetugasLoket::findOrFail($id);
        $penugasan->delete();

        return redirect()->route('admin.penugasan.index')->with('success', 'Penugasan petugas pada loket berhasil dilepaskan.');
    }
}