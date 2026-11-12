<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasLoket extends Model
{
    use HasFactory;

    protected $table = 'petugas_loket';
    protected $primaryKey = 'id_petugas_loket';

    protected $fillable = [
        'id_petugas',
        'id_loket',
    ];

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }

    public function loket()
    {
        return $this->belongsTo(Loket::class, 'id_loket', 'id_loket');
    }
}