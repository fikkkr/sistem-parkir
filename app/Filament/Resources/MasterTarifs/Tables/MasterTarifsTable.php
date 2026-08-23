<?php

namespace App\Filament\Resources\MasterTarifs\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MasterTarifsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenis_kendaraan')
                    ->label('Jenis Kendaraan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tipe_tarif')
                    ->label('Tipe Tarif')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'per_jam' => 'Per Jam',
                        'flat' => 'Flat',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'per_jam' => 'info',
                        'flat' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('tarif_per_jam')
                    ->label('Tarif / Jam')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('tarif_flat')
                    ->label('Tarif Flat')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}