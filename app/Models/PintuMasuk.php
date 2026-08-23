<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class PintuMasuk extends Model
{
    //
    protected $table = 'pintu_masuks';

    protected $fillable = [
        'kode_karcis',
        'plat_nomor',
        'waktu_masuk',
        'master_tarif_id',
        'status',
        'waktu_keluar',
        'durasi_jam',
        'total_bayar',
    ];

    protected function casts(): array
    {
        return [
            'waktu_masuk' => 'datetime',
            'waktu_keluar' => 'datetime',
            'durasi_jam' => 'integer',
            'total_bayar' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PintuMasuk $pintuMasuk): void {
            if (blank($pintuMasuk->kode_karcis)) {
                do {
                    $kodeKarcis = 'PKR-'.now()->format('Ymd').'-'.strtoupper(Str::random(4));
                } while (static::where('kode_karcis', $kodeKarcis)->exists());

                $pintuMasuk->kode_karcis = $kodeKarcis;
            }

            $pintuMasuk->waktu_masuk ??= now();
            $pintuMasuk->status ??= 'MASUK';
            $pintuMasuk->durasi_jam ??= 0;
            $pintuMasuk->total_bayar ??= 0;
        });
    }

    public function masterTarif(): BelongsTo
    {
        return $this->belongsTo(MasterTarif::class);
    }

    public function pintuKeluar(): HasOne
    {
        return $this->hasOne(PintuKeluar::class);
    }
}
