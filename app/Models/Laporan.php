<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laporan extends Model
{
    protected $table = 'laporans';
    
    protected $fillable = [
        'pintu_keluar_id',
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
    
    public function pintuKeluar(): BelongsTo
    {
        return $this->belongsTo(PintuKeluar::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
