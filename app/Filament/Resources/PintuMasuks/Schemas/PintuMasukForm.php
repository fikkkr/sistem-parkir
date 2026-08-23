<?php

namespace App\Filament\Resources\PintuMasuks\Schemas;

use App\Models\MasterTarif;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PintuMasukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(fn () => auth()->id()),

                TextInput::make('kode_karcis')
                    ->default(fn () => 'PKR-'.now()->format('Ymd').'-'.strtoupper(Str::random(4)))
                    ->readOnly()
                    ->required(),

                TextInput::make('plat_nomor')
                    ->label('Plat Nomor')
                    ->required()
                    ->placeholder('Contoh: B 1234 ABC'),

                Select::make('master_tarif_id')
                    ->label('Jenis Kendaraan')
                    ->options(fn () => MasterTarif::query()
                        ->get()
                        ->mapWithKeys(fn (MasterTarif $tarif) => [
                            $tarif->id => $tarif->jenis_kendaraan,
                        ]))
                    ->searchable()
                    ->preload()
                    ->required(),

                DateTimePicker::make('waktu_masuk')
                    ->label('Waktu Masuk')
                    ->default(now())
                    ->required(),
            ]);
    }
}
