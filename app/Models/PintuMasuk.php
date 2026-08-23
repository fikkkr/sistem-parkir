<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PintuMasuk extends Model
{
    //
    protected $table = 'pintu_masuks';
    
    protected $fillable = [
        'kode_karcis',
        'plat_nomor',
        'waktu_masuk',
    ];
}
