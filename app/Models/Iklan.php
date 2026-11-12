<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iklan extends Model
{
    use HasFactory;

    protected $table = 'iklan';
    protected $primaryKey = 'id_iklan';

    protected $fillable = [
        'judul',
        'deskripsi',
        'file_media',
        'tipe_media',
        'durasi_tampil',
        'urutan',
        'status_aktif',
    ];
}