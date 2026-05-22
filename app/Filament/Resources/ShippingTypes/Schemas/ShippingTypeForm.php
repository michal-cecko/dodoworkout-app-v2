<?php

namespace App\Filament\Resources\ShippingTypes\Schemas;

use App\Enums\ShippingTypeEnum;
use App\Filament\Concerns\HasTimestampsPlaceholders;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ShippingTypeForm
{
    use HasTimestampsPlaceholders;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Section::make('Základné údaje')
                    ->columns(12)
                    ->schema([
                        Tabs::make('Preklady')
                            ->columnSpanFull()
                            ->tabs([
                                Tab::make('SK')->schema([
                                    TextInput::make('name.sk')->label('Názov (SK)')->required(),
                                    Textarea::make('description.sk')->label('Popis (SK)')->rows(3),
                                ]),
                                Tab::make('EN')->schema([
                                    TextInput::make('name.en')->label('Názov (EN)'),
                                    Textarea::make('description.en')->label('Popis (EN)')->rows(3),
                                ]),
                            ]),

                        TextInput::make('price')
                            ->label('Cena')
                            ->numeric()
                            ->suffix('€')
                            ->minValue(0)
                            ->default(0)
                            ->columnSpan(4),

                        Select::make('type')
                            ->label('Typ')
                            ->options(ShippingTypeEnum::class)
                            ->columnSpan(4),

                        TextInput::make('icon')
                            ->label('Ikona (heroicon)')
                            ->placeholder('heroicon-o-truck')
                            ->columnSpan(4),

                        Toggle::make('is_hidden')
                            ->label('Skrytá')
                            ->columnSpan(12),
                    ]),

                Section::make('Časové údaje')
                    ->hiddenOn('create')
                    ->columns(12)
                    ->schema([
                        static::createdAtField()->columnSpan(6),
                        static::updatedAtField()->columnSpan(6),
                    ]),
            ]);
    }
}
