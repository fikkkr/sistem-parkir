<?php

namespace App\Filament\Resources\PintuKeluars\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PintuKeluarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pintuMasuk.kode_karcis')
                    ->label('Kode Karcis')
                    ->searchable(),

                TextColumn::make('pintuMasuk.plat_nomor')
                    ->label('Plat Nomor')
                    ->searchable(),

                TextColumn::make('waktu_keluar')
                    ->label('Waktu Keluar')
                    ->dateTime('d M Y - H:i'),

                TextColumn::make('durasi_jam')
                    ->label('Durasi')
                    ->suffix(' Jam'),

                TextColumn::make('total_bayar')
                    ->label('Total Biaya')
                    ->money('IDR')
                    ->state(fn ($record) => $record->total_bayar ?? $record->total_biaya),

                TextColumn::make('pintuMasuk.status')
                    ->label('Status')
                    ->badge()
                    ->placeholder(fn ($record) => $record->status_pembayaran),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
