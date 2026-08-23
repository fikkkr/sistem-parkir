<?php

namespace App\Filament\Resources\PintuKeluars\Schemas;

use App\Models\MasterTarif;
use App\Models\PintuMasuk;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PintuKeluarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(fn () => auth()->id()),

                Select::make('pintu_masuk_id')
                    ->label('Pilih Plat Nomor / Kode Karcis')
                    ->options(
                        PintuMasuk::doesntHave('pintuKeluar')
                            ->pluck('plat_nomor', 'id')
                    )
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if (! $state) return;

                        $masuk = PintuMasuk::find($state);
                        $waktuMasuk = Carbon::parse($masuk->waktu_masuk);
                        $waktuKeluar = $get('waktu_keluar') ? Carbon::parse($get('waktu_keluar')) : now();

                        $durasi = max(1, (int) ceil($waktuMasuk->diffInMinutes($waktuKeluar) / 60));

                        $tarif = MasterTarif::first();
                        $ratePerJam = $tarif ? $tarif->tarif_per_jam : 2000;
                        $total = $durasi * $ratePerJam;

                        $set('durasi_jam', $durasi);
                        $set('total_biaya', $total);
                    }),

                DateTimePicker::make('waktu_keluar')
                    ->label('Waktu Keluar')
                    ->default(now())
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $masukId = $get('pintu_masuk_id');
                        if (! $masukId) return;

                        $masuk = PintuMasuk::find($masukId);
                        $waktuMasuk = Carbon::parse($masuk->waktu_masuk);
                        $waktuKeluar = Carbon::parse($state);

                        $durasi = max(1, (int) ceil($waktuMasuk->diffInMinutes($waktuKeluar) / 60));

                        $tarif = MasterTarif::first();
                        $ratePerJam = $tarif ? $tarif->tarif_per_jam : 2000;
                        $total = $durasi * $ratePerJam;

                        $set('durasi_jam', $durasi);
                        $set('total_biaya', $total);
                    }),

                TextInput::make('durasi_jam')
                    ->label('Durasi Parkir')
                    ->numeric()
                    ->readOnly()
                    ->suffix('Jam'),

                TextInput::make('total_biaya')
                    ->label('Total Biaya')
                    ->numeric()
                    ->prefix('Rp')
                    ->readOnly(),

                TextInput::make('uang_bayar')
                    ->label('Uang Bayar')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $total = (float) $get('total_biaya');
                        $bayar = (float) $state;
                        $set('kembalian', max(0, $bayar - $total));
                    }),

                TextInput::make('kembalian')
                    ->label('Uang Kembalian')
                    ->numeric()
                    ->prefix('Rp')
                    ->readOnly(),
            ]);
    }
}
