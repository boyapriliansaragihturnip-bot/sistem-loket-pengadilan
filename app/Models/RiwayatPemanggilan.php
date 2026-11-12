<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPemanggilan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pemanggilan';
    protected $primaryKey = 'id_riwayat';

    protected $fillable = [
        'id_tiket',
        'id_petugas',
        'id_loket',
        'waktu_panggil',
        'jenis_aksi',
    ];

    public function tiket()
    {
        return $this->belongsTo(Tiket::class, 'id_tiket', 'id_tiket');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }

    public function loket()
    {
        return $this->belongsTo(Loket::class, 'id_loket', 'id_loket');
    }
}