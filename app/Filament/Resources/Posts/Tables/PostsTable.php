<?php

namespace App\Filament\Resources\Posts\Tables;

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

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')->label('Obrázok')->collection('image'),
                TextColumn::make('title')->label('Titulok')->searchable()->sortable(),
                IconColumn::make('is_published')
                    ->label('Publikovaný?')
                    ->sortable(['is_draft'])
                    ->boolean(),
                TextColumn::make('published_at')->label('Publikované')->dateTime('d.m.Y H:i')->sortable(),
                TextColumn::make('created_at')->label('Vytvorené')->dateTime('d.m.Y H:i')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
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
