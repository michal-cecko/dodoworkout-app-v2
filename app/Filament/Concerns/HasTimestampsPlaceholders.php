<?php

namespace App\Filament\Concerns;

use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Placeholder;

trait HasTimestampsPlaceholders
{
    protected static function createdAtField(): Placeholder
    {
        return Placeholder::make('created_at')
            ->label('Vytvorené dňa')
            ->content(fn (?\Illuminate\Database\Eloquent\Model $record): string => $record?->created_at?->format('d.m.Y H:i') ?? '—');
    }

    protected static function updatedAtField(): Placeholder
    {
        return Placeholder::make('updated_at')
            ->label('Posledná úprava')
            ->content(fn (?\Illuminate\Database\Eloquent\Model $record): string => $record?->updated_at?->diffForHumans() ?? '—');
    }
}
