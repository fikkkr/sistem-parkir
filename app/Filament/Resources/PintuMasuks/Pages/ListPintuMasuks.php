<?php

namespace App\Filament\Resources\PintuMasuks\Pages;

use App\Filament\Resources\PintuMasuks\PintuMasukResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPintuMasuks extends ListRecords
{
    protected static string $resource = PintuMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah data baru')
                ->icon('heroicon-o-plus'),
        ];
    }
}
