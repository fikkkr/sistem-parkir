<?php

namespace App\Filament\Resources\PintuKeluars\Pages;

use App\Filament\Resources\PintuKeluars\PintuKeluarResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPintuKeluars extends ListRecords
{
    protected static string $resource = PintuKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah data baru')
                ->icon('heroicon-o-plus'),
        ];
    }
}
