<?php

namespace App\Filament\Resources\PintuKeluars\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PintuKeluarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pintuMasuk.plat_nomor')
                    ->label('Plat Nomor')
                    ->searchable(),

                TextColumn::make('waktu_keluar')
                    ->label('Waktu Keluar')
                    ->dateTime('d M Y, H:i:s'),

                TextColumn::make('durasi_jam')
                    ->label('Durasi')
                    ->suffix(' Jam'),

                TextColumn::make('total_biaya')
                    ->label('Total Biaya')
                    ->money('IDR'),

                TextColumn::make('status_pembayaran')
                    ->label('Status')
                    ->badge(),
            ]);
    }
}