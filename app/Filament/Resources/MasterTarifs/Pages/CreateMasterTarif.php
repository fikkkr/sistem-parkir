<?php

namespace App\Filament\Resources\MasterTarifs\Pages;

use App\Filament\Resources\MasterTarifs\MasterTarifResource;
use Filament\Resources\Pages\CreateRecord;
use Override;

class CreateMasterTarif extends CreateRecord
{
    protected static string $resource = MasterTarifResource::class;

    #[Override]
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
