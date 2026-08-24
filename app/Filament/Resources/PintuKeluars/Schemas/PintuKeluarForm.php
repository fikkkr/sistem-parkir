<?php

namespace App\Filament\Resources\PintuKeluars\Schemas;

use App\Models\MasterTarif;
use App\Models\PintuMasuk;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PintuKeluarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(fn () => auth()->id()),

                // Select Kode Karcis yang disempurnakan dengan ->preload()
                Select::make('pintu_masuk_id')
                    ->label('Scan / Pilih Kode Karcis')
                    ->options(fn () => PintuMasuk::query()
                        ->where('status', 'MASUK')
                        ->pluck('kode_karcis', 'id'))
                    ->getOptionLabelUsing(fn ($value): ?string => PintuMasuk::find($value)?->kode_karcis)
                    ->searchable()
                    ->preload() // wajib ditambahkan agar opsi langsung muncul saat dropdown diklik
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        if (! $state) {
                            return;
                        }

                        $masuk = PintuMasuk::with('masterTarif')->find($state);
                        if (! $masuk) {
                            return;
                        }
                        $waktuMasuk = Carbon::parse($masuk->waktu_masuk);
                        $waktuKeluar = $get('waktu_keluar') ? Carbon::parse($get('waktu_keluar')) : now();

                        $durasi = max(1, (int) ceil($waktuMasuk->diffInMinutes($waktuKeluar) / 60));

                        $tarif = $masuk->masterTarif ?: MasterTarif::first();
                        $total = $tarif?->tipe_tarif === 'flat'
                            ? $tarif->tarif_flat
                            : $durasi * ($tarif?->tarif_per_jam ?? 2000);

                        $set('durasi_jam', $durasi);
                        $set('plat_nomor', $masuk->plat_nomor);
                        $set('waktu_masuk', $masuk->waktu_masuk);
                        $set('total_bayar', $total);
                        $set('total_biaya', $total);

                        // Recalculate kembalian jika user sudah terlanjur menginput uang bayar
                        $bayar = (float) $get('uang_bayar');
                        if ($bayar > 0) {
                            $set('kembalian', max(0, $bayar - $total));
                        }
                    }),

                DateTimePicker::make('waktu_keluar')
                    ->label('Waktu Keluar')
                    ->default(now())
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $masukId = $get('pintu_masuk_id');
                        if (! $masukId) {
                            return;
                        }

                        $masuk = PintuMasuk::with('masterTarif')->find($masukId);
                        if (! $masuk) {
                            return;
                        }
                        $waktuMasuk = Carbon::parse($masuk->waktu_masuk);
                        $waktuKeluar = Carbon::parse($state);

                        $durasi = max(1, (int) ceil($waktuMasuk->diffInMinutes($waktuKeluar) / 60));

                        $tarif = $masuk->masterTarif ?: MasterTarif::first();
                        $total = $tarif?->tipe_tarif === 'flat'
                            ? $tarif->tarif_flat
                            : $durasi * ($tarif?->tarif_per_jam ?? 2000);

                        $set('durasi_jam', $durasi);
                        $set('total_bayar', $total);
                        $set('total_biaya', $total);

                        // Recalculate kembalian saat waktu keluar diubah
                        $bayar = (float) $get('uang_bayar');
                        if ($bayar > 0) {
                            $set('kembalian', max(0, $bayar - $total));
                        }
                    }),

                TextInput::make('plat_nomor')
                    ->label('Plat Nomor')
                    ->readOnly(),

                DateTimePicker::make('waktu_masuk')
                    ->label('Waktu Masuk')
                    ->readOnly(),

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

                TextInput::make('total_bayar')
                    ->label('Total Bayar')
                    ->numeric()
                    ->prefix('Rp')
                    ->readOnly(),

                TextInput::make('uang_bayar')
                    ->label('Uang Bayar')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
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
