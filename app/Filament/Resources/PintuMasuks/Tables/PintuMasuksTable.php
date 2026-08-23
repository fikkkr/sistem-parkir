<?php

namespace App\Filament\Resources\PintuMasuks\Tables;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Picqer\Barcode\BarcodeGeneratorSVG;

class PintuMasuksTable
{
    public static function configure(Table $table): Table
    {
        $previewBarcode = fn (): Action => Action::make('previewBarcode')
            ->label('Preview Barcode')
            ->icon('heroicon-o-eye')
            ->modalHeading('Preview Barcode Karcis')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup')
            ->modalWidth('2xl')
            ->modalContent(fn ($record) => view('filament.resources.pintu-masuks.barcode-preview', [
                'record' => $record,
                'barcode' => new HtmlString(
                    (new BarcodeGeneratorSVG)->getBarcode(
                        $record->kode_karcis,
                        BarcodeGeneratorSVG::TYPE_CODE_128,
                    ),
                ),
            ]));

        return $table
            ->columns([
                TextColumn::make('kode_karcis')
                    ->label('Kode Karcis')
                    ->searchable(),

                TextColumn::make('barcode')
                    ->label('Barcode')
                    ->state(fn ($record) => $record->kode_karcis)
                    ->formatStateUsing(function (?string $state): HtmlString {
                        if (blank($state)) {
                            return new HtmlString('');
                        }

                        $generator = new BarcodeGeneratorSVG;

                        return new HtmlString($generator->getBarcode($state, $generator::TYPE_CODE_128));
                    })
                    ->html()
                    ->extraAttributes(['class' => 'cursor-pointer'])
                    ->action($previewBarcode()),

                TextColumn::make('plat_nomor')
                    ->label('Plat Nomor')
                    ->searchable(),

                TextColumn::make('waktu_masuk')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y, H:i:s')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
            ])
            ->actions([
                $previewBarcode(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
