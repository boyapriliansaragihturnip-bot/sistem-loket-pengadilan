<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;

    protected $table = 'petugas';
    protected $primaryKey = 'id_petugas';

    protected $fillable = [
        'user_id',
        'nip',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function petugasLoket()
    {
        return $this->hasMany(PetugasLoket::class, 'id_petugas', 'id_petugas');
    }

    public function riwayatPemanggilan()
    {
        return $this->hasMany(RiwayatPemanggilan::class, 'id_petugas', 'id_petugas');
    }
}