<?php

namespace App\Filament\Resources\PintuMasuks\Pages;

use App\Filament\Resources\PintuMasuks\PintuMasukResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePintuMasuk extends CreateRecord
{
    protected static string $resource = PintuMasukResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
