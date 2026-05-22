<?php

namespace App\Filament\Resources\Forms\Schemas;

use App\Enums\FormFieldFormat;
use App\Filament\Concerns\HasTimestampsPlaceholders;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class FormForm
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
                                    TextInput::make('name.sk')->label('Názov formuláru (SK)')->required(),
                                ]),
                                Tab::make('EN')->schema([
                                    TextInput::make('name.en')->label('Názov formuláru (EN)'),
                                ]),
                            ]),
                    ]),

                Section::make('Polia formuláru')
                    ->columns(12)
                    ->schema([
                        self::getFieldsRepeater()->columnSpanFull(),
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

    private static function getFieldsRepeater(): Repeater
    {
        $selectOrCheckbox = [FormFieldFormat::SELECT->value, FormFieldFormat::CHECKBOX->value];
        $isNumber = [FormFieldFormat::NUMBER->value];
        $isFile = [FormFieldFormat::FILE->value];
        $isDate = [FormFieldFormat::DATE->value, FormFieldFormat::DATETIME->value];

        $fieldSchema = [
            TextInput::make('label')->label('Názov poľa')->required()->columnSpan(6),
            Select::make('format')
                ->label('Formát')
                ->live()
                ->default(FormFieldFormat::TEXT->value)
                ->options(FormFieldFormat::translations())
                ->required()
                ->columnSpan(4),
            Checkbox::make('is_required')->label('Povinné?')->inline(false)->columnSpan(2),
            TextInput::make('help_text')->label('Popis / nápoveda')->columnSpan(12),

            TextInput::make('min_select')
                ->label('Min. výber')->numeric()
                ->afterStateHydrated(fn ($record, callable $set) => $set('min_select', $record?->min !== null ? (int) $record->min : null))
                ->visible(fn (Get $get) => in_array($get('format'), $selectOrCheckbox))
                ->columnSpan(6),
            TextInput::make('max_select')
                ->label('Max. výber')->numeric()
                ->afterStateHydrated(fn ($record, callable $set) => $set('max_select', $record?->max !== null ? (int) $record->max : null))
                ->visible(fn (Get $get) => in_array($get('format'), $selectOrCheckbox))
                ->columnSpan(6),

            TextInput::make('min_number')
                ->label('Min. hodnota')->numeric()
                ->afterStateHydrated(fn ($record, callable $set) => $set('min_number', $record?->min !== null ? (int) $record->min : null))
                ->visible(fn (Get $get) => in_array($get('format'), $isNumber))
                ->columnSpan(6),
            TextInput::make('max_number')
                ->label('Max. hodnota')->numeric()
                ->afterStateHydrated(fn ($record, callable $set) => $set('max_number', $record?->max !== null ? (int) $record->max : null))
                ->visible(fn (Get $get) => in_array($get('format'), $isNumber))
                ->columnSpan(6),

            TextInput::make('min_file_count')
                ->label('Min. počet súborov')->numeric()
                ->afterStateHydrated(fn ($record, callable $set) => $set('min_file_count', $record?->min !== null ? (int) $record->min : null))
                ->visible(fn (Get $get) => in_array($get('format'), $isFile))
                ->columnSpan(6),
            TextInput::make('max_file_count')
                ->label('Max. počet súborov')->numeric()
                ->afterStateHydrated(fn ($record, callable $set) => $set('max_file_count', $record?->max !== null ? (int) $record->max : null))
                ->visible(fn (Get $get) => in_array($get('format'), $isFile))
                ->columnSpan(6),

            DateTimePicker::make('min_date')
                ->label('Min. dátum')->native(false)
                ->afterStateHydrated(fn ($record, callable $set) => $set('min_date', $record?->min ?: null))
                ->visible(fn (Get $get) => in_array($get('format'), $isDate))
                ->columnSpan(6),
            DateTimePicker::make('max_date')
                ->label('Max. dátum')->native(false)
                ->afterStateHydrated(fn ($record, callable $set) => $set('max_date', $record?->max ?: null))
                ->visible(fn (Get $get) => in_array($get('format'), $isDate))
                ->columnSpan(6),

            Repeater::make('options')
                ->label('Možnosti')
                ->columnSpanFull()
                ->addActionLabel('Pridať možnosť')
                ->grid(4)
                ->cloneable()
                ->reorderableWithButtons()
                ->collapsible()
                ->visible(fn (Get $get) => in_array($get('format'), $selectOrCheckbox))
                ->deleteAction(fn (Action $action) => $action->requiresConfirmation())
                ->itemLabel(fn (array $state): ?string => $state['value'] ?? null)
                ->schema([
                    TextInput::make('value')->label('Hodnota')->required()->columnSpanFull(),
                ]),
        ];

        return Repeater::make('formFields')
            ->label('Polia formuláru')
            ->relationship()
            ->columnSpanFull()
            ->columns(12)
            ->addActionLabel('Pridať nové pole')
            ->cloneable()
            ->hint(fn (string $operation) => $operation === 'create'
                ? 'Ak chcete skopírovať polia do iného jazyka, uložte formulár v hlavnom jazyku a pri úprave použite možnosť "Kopírovať jazykovú mutáciu".'
                : null)
            ->reorderableWithButtons()
            ->collapsible()
            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
            ->deleteAction(fn (Action $action) => $action->requiresConfirmation())
            ->schema($fieldSchema);
    }
}
