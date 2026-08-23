<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PintuKeluar extends Model
{
    //
    protected $table = 'pintu_keluars';

    protected $fillable = [
        'pintu_masuk_id',
        'user_id',
        'waktu_keluar',
        'durasi_jam',
        'total_biaya',
        'uang_bayar',
        'kembalian',
        'status_pembayaran',
    ];
}
