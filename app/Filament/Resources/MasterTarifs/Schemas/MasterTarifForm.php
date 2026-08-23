<?php

namespace App\Filament\Resources\MasterTarifs\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class MasterTarifForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(fn () => auth()->id()),

                TextInput::make('jenis_kendaraan')
                    ->label('Jenis Kendaraan')
                    ->default('Motor')
                    ->required(),

                Select::make('tipe_tarif')
                    ->label('Tipe Perhitungan Tarif')
                    ->options([
                        'per_jam' => 'Tarif Per Jam (Progresif)',
                        'flat' => 'Tarif Flat (Sekali Bayar)',
                    ])
                    ->default('per_jam')
                    ->required()
                    ->live(),

                // Tarif Per Jam: Wajib diisi & Minimal Rp 1 jika Tipe Tarif = per_jam
                TextInput::make('tarif_per_jam')
                    ->label('Tarif Per Jam')
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(1) // Tidak boleh 0 atau minus
                    ->required(fn (Get $get) => $get('tipe_tarif') === 'per_jam')
                    ->visible(fn (Get $get) => $get('tipe_tarif') === 'per_jam'),

                // Tarif Flat: Wajib diisi & Minimal Rp 1 jika Tipe Tarif = flat
                TextInput::make('tarif_flat')
                    ->label('Tarif Flat')
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(1) // Tidak boleh 0 atau minus
                    ->required(fn (Get $get) => $get('tipe_tarif') === 'flat')
                    ->visible(fn (Get $get) => $get('tipe_tarif') === 'flat'),
            ]);
    }
}