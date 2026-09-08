<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporans';
    
    protected $fillable = [
        'tanggal_mulai',
        'tanggal_selesai',
        'total_transaksi',
        'total_pendapatan',
        'user_id',
    ];
    
    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'total_transaksi' => 'integer',
        'total_pendapatan' => 'decimal:2',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
