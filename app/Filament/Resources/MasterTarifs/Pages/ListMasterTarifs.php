<?php

namespace App\Filament\Resources\MasterTarifs\Pages;

use App\Filament\Resources\MasterTarifs\MasterTarifResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMasterTarifs extends ListRecords
{
    protected static string $resource = MasterTarifResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah data baru')
                ->icon('heroicon-o-plus'),
        ];
    }
}
