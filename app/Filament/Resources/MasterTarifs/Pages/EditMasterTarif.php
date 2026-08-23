<?php

namespace App\Filament\Resources\MasterTarifs\Pages;

use App\Filament\Resources\MasterTarifs\MasterTarifResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterTarif extends EditRecord
{
    protected static string $resource = MasterTarifResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}