<?php

namespace App\Filament\Resources\PintuMasuks\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
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
                    ->default(fn () => 'KRC-' . strtoupper(Str::random(6)))
                    ->readOnly()
                    ->required(),

                TextInput::make('plat_nomor')
                    ->label('Plat Nomor')
                    ->required()
                    ->placeholder('Contoh: B 1234 ABC'),

                DateTimePicker::make('waktu_masuk')
                    ->label('Waktu Masuk')
                    ->default(now())
                    ->required(),
            ]);
    }
}