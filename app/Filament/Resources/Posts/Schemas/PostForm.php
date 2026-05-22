<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Enums\Locale;
use App\Filament\Concerns\HasTimestampsPlaceholders;
use App\Filament\Concerns\UseContentBuilder;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PostForm
{
    use HasTimestampsPlaceholders, UseContentBuilder;

    public static function configure(Schema $schema): Schema
    {
        $locales = collect(Locale::cases())
            ->mapWithKeys(fn (Locale $l) => [$l->value => $l->value])
            ->toArray();

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
                                    TextInput::make('title.sk')->label('Titulok (SK)')->required(),
                                    Textarea::make('excerpt.sk')
                                        ->label('Popis (SK)')
                                        ->hint('Zobrazuje sa na kartách na domovskej stránke.')
                                        ->rows(3),
                                ]),
                                Tab::make('EN')->schema([
                                    TextInput::make('title.en')->label('Titulok (EN)'),
                                    Textarea::make('excerpt.en')->label('Popis (EN)')->rows(3),
                                ]),
                            ]),

                        SpatieMediaLibraryFileUpload::make('image')
                            ->label('Obrázok')
                            ->collection('image')
                            ->disk('public')
                            ->preserveFilenames()
                            ->required()
                            ->imageEditor()
                            ->columnSpanFull(),

                        Select::make('tags')
                            ->label('Značky')
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->columnSpan(8),

                        Select::make('locale_scope')
                            ->label('Článok dostupný v jazykoch')
                            ->placeholder('Všetky jazyky')
                            ->options($locales)
                            ->columnSpan(4),

                        Checkbox::make('is_draft')
                            ->label('Uložiť ako koncept?')
                            ->inline(false)
                            ->live()
                            ->columnSpan(4),

                        DateTimePicker::make('published_at')
                            ->label('Zverejnené dňa')
                            ->native(false)
                            ->weekStartsOnMonday()
                            ->default(now())
                            ->required()
                            ->visible(fn (Get $get) => ! $get('is_draft'))
                            ->columnSpan(8),
                    ]),

                Section::make('Obsah článku')
                    ->columns(12)
                    ->schema([
                        static::getContentBuilder(fieldLabel: 'Obsah článku'),
                    ]),

                Section::make('Časové údaje a metrika')
                    ->hiddenOn('create')
                    ->columns(12)
                    ->schema([
                        static::createdAtField()->columnSpan(4),
                        static::updatedAtField()->columnSpan(4),
                        Placeholder::make('likes_metric')
                            ->label('Hodnotenia článku')
                            ->content(fn ($record) => $record ? "dobré: {$record->likes}, zlé: {$record->dislikes}" : '—')
                            ->visible(fn (Get $get, $record) => $record && ! $get('is_draft'))
                            ->columnSpan(4),
                    ]),
            ]);
    }
}
