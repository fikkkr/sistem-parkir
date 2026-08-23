<?php

namespace App\Filament\Resources\PintuKeluars;

use App\Filament\Resources\PintuKeluars\Pages\CreatePintuKeluar;
use App\Filament\Resources\PintuKeluars\Pages\EditPintuKeluar;
use App\Filament\Resources\PintuKeluars\Pages\ListPintuKeluars;
use App\Filament\Resources\PintuKeluars\Schemas\PintuKeluarForm;
use App\Filament\Resources\PintuKeluars\Tables\PintuKeluarsTable;
use App\Models\PintuKeluar;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PintuKeluarResource extends Resource
{
    protected static ?string $model = PintuKeluar::class;

    protected static ?string $modelLabel = 'Pintu keluar';

    protected static ?string $pluralModelLabel = 'Pintu keluar';

    protected static ?string $navigationLabel = 'Bayar';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PintuKeluarForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PintuKeluarsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('pintuMasuk');
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
            'index' => ListPintuKeluars::route('/'),
            'create' => CreatePintuKeluar::route('/create'),
            'edit' => EditPintuKeluar::route('/{record}/edit'),
        ];
    }
}
