<?php

namespace App\Filament\Resources\PintuMasuks\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PintuMasuksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_karcis')
                    ->label('Kode Karcis')
                    ->searchable(),

                TextColumn::make('plat_nomor')
                    ->label('Plat Nomor')
                    ->searchable(),

                TextColumn::make('waktu_masuk')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y, H:i:s')
                    ->sortable(),
            ]);
    }
}