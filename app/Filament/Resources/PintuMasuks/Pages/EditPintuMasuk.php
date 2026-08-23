<?php

namespace App\Filament\Resources\PintuMasuks\Pages;

use App\Filament\Resources\PintuMasuks\PintuMasukResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPintuMasuk extends EditRecord
{
    protected static string $resource = PintuMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
