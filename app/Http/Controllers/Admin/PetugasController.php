<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Petugas;

class PetugasController extends Controller
{
    public function index()
    {
        $petugasList = Petugas::with(['user', 'petugasLoket.loket'])->orderBy('id_petugas', 'asc')->get();
        return view('admin.petugas.index', compact('petugasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'nip' => 'required|string|max:18|unique:petugas,nip',
            'password' => 'required|string|min:6',
        ], [
            'nama.required' => 'Nama petugas wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'nip.unique' => 'NIP ini sudah terdaftar.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->nama, // Sesuaikan jika kolom di tabel users Anda bernama 'name' atau 'nama'
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'petugas',
            ]);

            Petugas::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
            ]);
        });

        return redirect()->route('admin.petugas.index')->with('success', 'Akun petugas berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $petugas = Petugas::with('user')->findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $petugas->user_id . ',id',
            'nip' => 'required|string|max:18|unique:petugas,nip,' . $id . ',id_petugas',
            'password' => 'nullable|string|min:6',
        ]);

        DB::transaction(function () use ($request, $petugas) {
            $petugas->user->update([
                'name' => $request->nama, // Pastikan sesuai kolom tabel users
                'email' => $request->email,
                'password' => $request->filled('password') ? Hash::make($request->password) : $petugas->user->password,
            ]);

            $petugas->update([
                'nip' => $request->nip,
            ]);
        });

        return redirect()->route('admin.petugas.index')->with('success', 'Data petugas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $petugas = Petugas::withCount(['petugasLoket', 'riwayatPemanggilan'])->findOrFail($id);

        if ($petugas->riwayat_pemanggilan_count > 0) {
            return redirect()->route('admin.petugas.index')->with('error', 'Petugas tidak dapat dihapus karena memiliki riwayat pemanggilan antrean.');
        }

        DB::transaction(function () use ($petugas) {
            $userId = $petugas->user_id;
            $petugas->delete();
            User::where('id', $userId)->delete();
        });

        return redirect()->route('admin.petugas.index')->with('success', 'Akun petugas berhasil dihapus.');
    }
}