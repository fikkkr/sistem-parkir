<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterTarif extends Model
{
    //
    protected $table = 'master_tarifs';

    protected $fillable = [
        'user_id',
        'jenis_kendaraan',
        'tipe_tarif',
        'tarif_per_jam',
        'tarif_flat',
    ];  
}   