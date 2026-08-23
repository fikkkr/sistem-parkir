<?php

namespace App\Filament\Resources\PintuKeluars\Pages;

use App\Filament\Resources\PintuKeluars\PintuKeluarResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePintuKeluar extends CreateRecord
{
    protected static string $resource = PintuKeluarResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['total_biaya'] = $data['total_bayar'] ?? $data['total_biaya'] ?? 0;

        return $data;
    }
}
