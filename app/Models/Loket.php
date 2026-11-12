<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loket extends Model
{
    use HasFactory;

    protected $table = 'loket';
    protected $primaryKey = 'id_loket';

    protected $fillable = [
        'id_layanan',
        'nama_loket',
        'status_aktif',
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }

    public function petugasLoket()
    {
        return $this->hasOne(PetugasLoket::class, 'id_loket', 'id_loket');
    }

    public function tiket()
    {
        return $this->hasMany(Tiket::class, 'id_loket', 'id_loket');
    }

    public function riwayatPemanggilan()
    {
        return $this->hasMany(RiwayatPemanggilan::class, 'id_loket', 'id_loket');
    }
}