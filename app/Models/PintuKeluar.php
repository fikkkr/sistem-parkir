<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PintuKeluar extends Model
{
    //
    protected $table = 'pintu_keluars';

    public $timestamps = false;

    protected $fillable = [
        'pintu_masuk_id',
        'user_id',
        'waktu_keluar',
        'durasi_jam',
        'total_biaya',
        'total_bayar',
        'uang_bayar',
        'kembalian',
        'status_pembayaran',
    ];

    protected function casts(): array
    {
        return [
            'waktu_keluar' => 'datetime',
            'durasi_jam' => 'integer',
            'total_biaya' => 'decimal:2',
            'total_bayar' => 'decimal:2',
            'uang_bayar' => 'decimal:2',
            'kembalian' => 'decimal:2',
        ];
    }

    public function pintuMasuk(): BelongsTo
    {
        return $this->belongsTo(PintuMasuk::class);
    }

    protected static function booted(): void
    {
        static::created(function (PintuKeluar $pintuKeluar): void {
            $pintuKeluar->pintuMasuk?->update([
                'waktu_keluar' => $pintuKeluar->waktu_keluar,
                'durasi_jam' => $pintuKeluar->durasi_jam,
                'total_bayar' => $pintuKeluar->total_bayar ?? $pintuKeluar->total_biaya,
                'status' => 'SELESAI',
            ]);
        });
    }
}
