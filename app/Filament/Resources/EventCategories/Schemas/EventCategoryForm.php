<?php

namespace App\Filament\Resources\EventCategories\Schemas;

use App\Filament\Concerns\HasTimestampsPlaceholders;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class EventCategoryForm
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
                                ]),
                                Tab::make('EN')->schema([
                                    TextInput::make('name.en')->label('Názov (EN)'),
                                ]),
                            ]),
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
