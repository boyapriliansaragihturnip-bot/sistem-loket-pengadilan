<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaturan;

class PengaturanController extends Controller
{
    // Tampilkan Halaman Pengaturan
    public function index()
    {
        $settings = Pengaturan::pluck('setting_value', 'setting_key')->toArray();
        return view('admin.pengaturan.index', compact('settings'));
    }

    // Simpan Perubahan Pengaturan
    public function update(Request $request)
    {
        $request->validate([
            'nama_pengadilan' => 'required|string|max:150',
            'alamat_pengadilan' => 'required|string|max:255',
            'telepon_pengadilan' => 'nullable|string|max:30',
            'pimpinan_pengadilan' => 'nullable|string|max:100',
        ]);

        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            Pengaturan::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => $value ?? '']
            );
        }

        return redirect()->route('admin.pengaturan.index')->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}