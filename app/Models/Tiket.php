<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;

    protected $table = 'tiket';
    protected $primaryKey = 'id_tiket';

    protected $fillable = [
        'id_loket',
        'id_pengguna',
        'nomor_urut',
        'kode_booking',
        'tipe_antrean',
        'tanggal_tiket',
        'jam_prakiraan',
        'status',
    ];

    public function loket()
    {
        return $this->belongsTo(Loket::class, 'id_loket', 'id_loket');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function riwayatPemanggilan()
    {
        return $this->hasMany(RiwayatPemanggilan::class, 'id_tiket', 'id_tiket');
    }

    // TAMBAHKAN INI: Relasi tembus (through) ke Layanan melalui Loket
    public function layanan()
    {
        return $this->hasOneThrough(
            Layanan::class, 
            Loket::class, 
            'id_loket',    // Foreign key di tabel loket...
            'id_layanan',  // Foreign key di tabel layanan...
            'id_loket',    // Local key di tabel tiket...
            'id_layanan'   // Local key di tabel loket...
        );
    }
}