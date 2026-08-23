<?php

namespace App\Filament\Resources\MasterTarifs;

use App\Filament\Resources\MasterTarifs\Pages\CreateMasterTarif;
use App\Filament\Resources\MasterTarifs\Pages\EditMasterTarif;
use App\Filament\Resources\MasterTarifs\Pages\ListMasterTarifs;
use App\Filament\Resources\MasterTarifs\Schemas\MasterTarifForm;
use App\Filament\Resources\MasterTarifs\Tables\MasterTarifsTable;
use App\Models\MasterTarif;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MasterTarifResource extends Resource
{
    protected static ?string $model = MasterTarif::class;
    protected static ?string $modelLabel = 'Master tarif';
    protected static ?string $pluralModelLabel = 'Master tarif';
    protected static ?string $navigationLabel = 'Tarif Parkir';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return MasterTarifForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MasterTarifsTable::configure($table);
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
            'index' => ListMasterTarifs::route('/'),
            'create' => CreateMasterTarif::route('/create'),
            'edit' => EditMasterTarif::route('/{record}/edit'),
        ];
    }
}
