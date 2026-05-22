<?php

namespace App\Filament\Resources\Events\Tables;

use App\Filament\Actions\CopyLocaleFieldsAction;
use App\Filament\Actions\FrontendViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')->label('Obrázok')->collection('image'),
                TextColumn::make('title')->label('Titulok')->searchable()->sortable(),
                TextColumn::make('category.name')->label('Kategória')->sortable()->searchable(),
                TextColumn::make('start_at')->label('Začiatok')->dateTime('d.m.Y H:i')->sortable(),
                TextColumn::make('price')->label('Cena (€)')->numeric()->sortable(),
                IconColumn::make('is_published')
                    ->label('Publikovaný?')
                    ->sortable(['is_draft'])
                    ->boolean(),
            ])
            ->defaultSort('start_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                FrontendViewAction::make(),
                CopyLocaleFieldsAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
