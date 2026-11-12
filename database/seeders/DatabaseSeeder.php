<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Petugas;
use App\Models\Layanan;
use App\Models\Loket;
use App\Models\PetugasLoket;
use App\Models\Pengaturan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Pengaturan Sistem Awal
            $pengaturan = [
                ['setting_key' => 'nama_pengadilan', 'setting_value' => 'Pengadilan Negeri Kelas IA Tanjungkarang'],
                ['setting_key' => 'alamat_pengadilan', 'setting_value' => 'Jl. Wolter Monginsidi No.5, Teluk Betung, Bandar Lampung'],
                ['setting_key' => 'telepon_pengadilan', 'setting_value' => '(0721) 123456'],
                ['setting_key' => 'email_pengadilan', 'setting_value' => 'info@pn-tanjungkarang.go.id'],
            ];

            foreach ($pengaturan as $item) {
                Pengaturan::updateOrCreate(['setting_key' => $item['setting_key']], $item);
            }

            // 2. Buat Akun Admin
            $adminUser = User::updateOrCreate(
                ['email' => 'admin@pengadilan.go.id'],
                [
                    'nama' => 'Administrator Sistem',
                    'password' => Hash::make('password123'),
                    'role' => 'admin',
                ]
            );

            // 3. Buat Data Layanan Pengadilan
            $layananPerdata = Layanan::updateOrCreate(
                ['nama_layanan' => 'Perdata'],
                [
                    'deskripsi' => 'Layanan pendaftaran perkara perdata, persidangan, dan produk hukum perdata.',
                    'status_aktif' => true,
                ]
            );

            $layananPidana = Layanan::updateOrCreate(
                ['nama_layanan' => 'Pidana'],
                [
                    'deskripsi' => 'Layanan administrasi perkara pidana dan pelimpahan berkas sidang pidana.',
                    'status_aktif' => true,
                ]
            );

            $layananInformasi = Layanan::updateOrCreate(
                ['nama_layanan' => 'Informasi'],
                [
                    'deskripsi' => 'Layanan informasi perkara, e-court, dan pelayanan terpadu satu pintu (PTSP).',
                    'status_aktif' => true,
                ]
            );

            $layananPengaduan = Layanan::updateOrCreate(
                ['nama_layanan' => 'Pengaduan'],
                [
                    'deskripsi' => 'Layanan pos pengaduan masyarakat dan mediasi sengketa.',
                    'status_aktif' => true,
                ]
            );

            // 4. Buat Data Loket
            $loket1 = Loket::updateOrCreate(
                ['nama_loket' => 'Loket 1'],
                ['id_layanan' => $layananPerdata->id_layanan, 'status_aktif' => true]
            );

            $loket2 = Loket::updateOrCreate(
                ['nama_loket' => 'Loket 2'],
                ['id_layanan' => $layananPidana->id_layanan, 'status_aktif' => true]
            );

            $loket3 = Loket::updateOrCreate(
                ['nama_loket' => 'Loket 3'],
                ['id_layanan' => $layananInformasi->id_layanan, 'status_aktif' => true]
            );

            $loket4 = Loket::updateOrCreate(
                ['nama_loket' => 'Loket 4'],
                ['id_layanan' => $layananPengaduan->id_layanan, 'status_aktif' => true]
            );

            // 5. Buat Akun Petugas Contoh & Penugasan Loket
            $daftarPetugas = [
                ['nama' => 'Bima Pratama', 'email' => 'bima@pengadilan.go.id', 'nip' => '199001012015031001', 'loket_id' => $loket1->id_loket],
                ['nama' => 'Agus Setiawan', 'email' => 'agus@pengadilan.go.id', 'nip' => '199205122018031002', 'loket_id' => $loket2->id_loket],
                ['nama' => 'Andi Saputra', 'email' => 'andi@pengadilan.go.id', 'nip' => '199408202020121003', 'loket_id' => $loket3->id_loket],
                ['nama' => 'Rina Melati', 'email' => 'rina@pengadilan.go.id', 'nip' => '199511152021022004', 'loket_id' => $loket4->id_loket],
            ];

            foreach ($daftarPetugas as $data) {
                $userPetugas = User::updateOrCreate(
                    ['email' => $data['email']],
                    [
                        'nama' => $data['nama'],
                        'password' => Hash::make('password123'),
                        'role' => 'petugas',
                    ]
                );

                $petugas = Petugas::updateOrCreate(
                    ['user_id' => $userPetugas->id],
                    ['nip' => $data['nip']]
                );

                PetugasLoket::updateOrCreate(
                    ['id_loket' => $data['loket_id']],
                    ['id_petugas' => $petugas->id_petugas]
                );
            }
        });
    }
}