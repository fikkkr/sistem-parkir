<?php

namespace App\Filament\Resources\PintuMasuks;

use App\Filament\Resources\PintuMasuks\Pages\CreatePintuMasuk;
use App\Filament\Resources\PintuMasuks\Pages\EditPintuMasuk;
use App\Filament\Resources\PintuMasuks\Pages\ListPintuMasuks;
use App\Filament\Resources\PintuMasuks\Schemas\PintuMasukForm;
use App\Filament\Resources\PintuMasuks\Tables\PintuMasuksTable;
use App\Models\PintuMasuk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PintuMasukResource extends Resource
{
    protected static ?string $model = PintuMasuk::class;
    protected static ?string $modelLabel = 'Pintu masuk';
    protected static ?string $pluralModelLabel = 'Pintu masuk';
    protected static ?string $navigationLabel = 'Buat Karcis';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PintuMasukForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PintuMasuksTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('masterTarif');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPintuMasuks::route('/'),
            'create' => CreatePintuMasuk::route('/create'),
            'edit' => EditPintuMasuk::route('/{record}/edit'),
        ];
    }
}
