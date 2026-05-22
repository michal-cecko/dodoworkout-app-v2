<?php

namespace App\Filament\Resources\ShippingTypes;

use App\Filament\Resources\ShippingTypes\Pages\CreateShippingType;
use App\Filament\Resources\ShippingTypes\Pages\EditShippingType;
use App\Filament\Resources\ShippingTypes\Pages\ListShippingTypes;
use App\Filament\Resources\ShippingTypes\Schemas\ShippingTypeForm;
use App\Filament\Resources\ShippingTypes\Tables\ShippingTypesTable;
use App\Models\ShippingType;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ShippingTypeResource extends Resource
{
    protected static ?string $model = ShippingType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static ?string $modelLabel = 'Spôsob dopravy';

    protected static ?string $pluralModelLabel = 'Spôsoby dopravy';

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static UnitEnum|string|null $navigationGroup = 'Objednávky';

    protected static ?int $navigationSort = 31;

    public static function form(Schema $schema): Schema
    {
        return ShippingTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShippingTypesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShippingTypes::route('/'),
            'create' => CreateShippingType::route('/create'),
            'edit' => EditShippingType::route('/{record}/edit'),
        ];
    }
}
