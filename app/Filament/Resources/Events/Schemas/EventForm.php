<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Enums\Locale;
use App\Filament\Concerns\HasTimestampsPlaceholders;
use App\Filament\Concerns\UseContentBuilder;
use App\Models\Form;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class EventForm
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
                                    TextInput::make('order_item_name.sk')->label('Názov položky v objednávke (SK)')->required(),
                                    Textarea::make('excerpt.sk')->label('Popis (SK)')->rows(3),
                                    Textarea::make('address.sk')->label('Adresa (SK)')->rows(2),
                                ]),
                                Tab::make('EN')->schema([
                                    TextInput::make('title.en')->label('Titulok (EN)'),
                                    TextInput::make('order_item_name.en')->label('Názov položky v objednávke (EN)'),
                                    Textarea::make('excerpt.en')->label('Popis (EN)')->rows(3),
                                    Textarea::make('address.en')->label('Adresa (EN)')->rows(2),
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

                        Select::make('category_id')
                            ->label('Kategória')
                            ->relationship('category', 'name')
                            ->preload()
                            ->searchable()
                            ->columnSpan(8),

                        Select::make('locale_scope')
                            ->label('Event dostupný v jazykoch')
                            ->placeholder('Všetky jazyky')
                            ->options($locales)
                            ->columnSpan(4),

                        Checkbox::make('is_draft')
                            ->label('Uložiť ako koncept?')
                            ->inline(false)
                            ->live()
                            ->columnSpan(4),
                    ]),

                Section::make('Termín a kapacita')
                    ->columns(12)
                    ->schema([
                        DateTimePicker::make('start_at')
                            ->label('Začiatok eventu')
                            ->native(false)
                            ->weekStartsOnMonday()
                            ->required()
                            ->columnSpan(6),
                        DateTimePicker::make('end_at')
                            ->label('Koniec eventu')
                            ->native(false)
                            ->weekStartsOnMonday()
                            ->columnSpan(6),
                        TextInput::make('participants_count')
                            ->label('Max. počet účastníkov')
                            ->numeric()
                            ->minValue(1)
                            ->columnSpan(4),
                        TextInput::make('price')
                            ->label('Cena')
                            ->numeric()
                            ->suffix('€')
                            ->minValue(0)
                            ->columnSpan(4),
                        TextInput::make('last_price')
                            ->label('Cena pred zľavou')
                            ->numeric()
                            ->suffix('€')
                            ->minValue(0)
                            ->columnSpan(4),
                    ]),

                Section::make('Lokalita')
                    ->columns(12)
                    ->schema([
                        Checkbox::make('has_location')
                            ->label('Pridať lokalitu na mape?')
                            ->live()
                            ->columnSpanFull(),
                        // TODO: install dotswan/filament-map-picker v2+ to wire a real map widget.
                        TextInput::make('latitude')
                            ->label('Zemepisná šírka')
                            ->numeric()
                            ->visible(fn (Get $get) => $get('has_location'))
                            ->columnSpan(6),
                        TextInput::make('longitude')
                            ->label('Zemepisná dĺžka')
                            ->numeric()
                            ->visible(fn (Get $get) => $get('has_location'))
                            ->columnSpan(6),
                    ]),

                Section::make('Formulár prihlasovania')
                    ->columns(12)
                    ->schema([
                        Select::make('form_id')
                            ->label('Formulár')
                            ->options(fn () => Form::query()->pluck('name', 'id'))
                            ->searchable()
                            ->columnSpanFull(),
                    ]),

                Section::make('Potvrdzovací email')
                    ->columns(12)
                    ->collapsed()
                    ->schema([
                        Tabs::make('Preklady emailu')
                            ->columnSpanFull()
                            ->tabs([
                                Tab::make('SK')->schema([
                                    RichEditor::make('confirmation_email_content.sk')
                                        ->label('Text k potvrdzovaciemu emailu (SK)'),
                                ]),
                                Tab::make('EN')->schema([
                                    RichEditor::make('confirmation_email_content.en')
                                        ->label('Text k potvrdzovaciemu emailu (EN)'),
                                ]),
                            ]),
                        SpatieMediaLibraryFileUpload::make('confirmation_email_attachments')
                            ->label('Prílohy k potvrdzovaciemu emailu')
                            ->collection('confirmation_email_attachments')
                            ->disk('public')
                            ->preserveFilenames()
                            ->multiple()
                            ->columnSpanFull(),
                    ]),

                Section::make('Obsah eventu')
                    ->columns(12)
                    ->schema([
                        static::getContentBuilder(fieldLabel: 'Obsah eventu'),
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
