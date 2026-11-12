<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Layanan;

class LayananController extends Controller
{
    public function index()
    {
        $layananList = Layanan::orderBy('id_layanan', 'asc')->get();
        return view('admin.layanan.index', compact('layananList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:255',
        ], [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'nama_layanan.max' => 'Nama layanan maksimal 100 karakter.',
        ]);

        Layanan::create([
            'nama_layanan' => $request->nama_layanan,
            'deskripsi' => $request->deskripsi,
            'status_aktif' => $request->has('status_aktif') ? true : false,
        ]);

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    // Perhatikan parameter $id atau gunakan Route Model Binding dengan penyesuaian key
    public function update(Request $request, $id)
    {
        $layanan = Layanan::findOrFail($id);

        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:255',
        ], [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
        ]);

        $layanan->update([
            'nama_layanan' => $request->nama_layanan,
            'deskripsi' => $request->deskripsi,
            'status_aktif' => $request->has('status_aktif') ? true : false,
        ]);

        return redirect()->route('admin.layanan.index')->with('success', 'Data layanan berhasil diperbarui.');
    }

    public function toggleStatus($id)
    {
        $layanan = Layanan::findOrFail($id);
        $layanan->status_aktif = !$layanan->status_aktif;
        $layanan->save();

        $statusText = $layanan->status_aktif ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.layanan.index')->with('success', "Status Layanan {$layanan->nama_layanan} berhasil {$statusText}.");
    }

    public function destroy($id)
    {
        $layanan = Layanan::withCount('loket')->findOrFail($id);

        if ($layanan->loket_count > 0) {
            return redirect()->route('admin.layanan.index')->with('error', 'Layanan tidak dapat dihapus karena masih terikat dengan data loket aktif. Nonaktifkan layanan sebagai alternatif.');
        }

        $layanan->delete();
        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil dihapus dari sistem.');
    }
}