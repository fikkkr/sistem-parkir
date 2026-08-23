<?php

namespace App\Filament\Resources\PintuKeluars\Pages;

use App\Filament\Resources\PintuKeluars\PintuKeluarResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPintuKeluar extends EditRecord
{
    protected static string $resource = PintuKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
