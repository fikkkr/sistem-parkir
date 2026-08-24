<?php

namespace App\Filament\Resources\PintuKeluars\Pages;

use App\Filament\Resources\PintuKeluars\PintuKeluarResource;
use Filament\Resources\Pages\ListRecords;

class ListPintuKeluars extends ListRecords
{
    protected static string $resource = PintuKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
