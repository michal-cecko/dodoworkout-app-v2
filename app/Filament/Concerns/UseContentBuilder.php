<?php

namespace App\Filament\Concerns;

use Filament\Actions\Action;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Storage;

trait UseContentBuilder
{
    public static function getContentBuilder(string $fieldLabel = 'Obsah', string $fieldKey = 'content'): Builder
    {
        return Builder::make($fieldKey)
            ->label($fieldLabel)
            ->blocks(self::getContentBuilderBlocks())
            ->addActionLabel('Pridať nový blok')
            ->reorderableWithButtons()
            ->collapsible()
            ->deleteAction(fn (Action $action) => $action->requiresConfirmation())
            ->columnSpanFull();
    }

    public static function getContentBuilderBlocks(): array
    {
        return [
            Block::make('content')
                ->label('Text')
                ->schema([
                    RichEditor::make('content')
                        ->required(),
                ]),
            Block::make('media')
                ->label('Obrázok / Video')
                ->schema([
                    FileUpload::make('media')
                        ->label('Médium')
                        ->disk('public')
                        ->directory(fn ($record) => self::getMediaDirectory($record))
                        ->required()
                        ->preserveFilenames()
                        ->imageEditor(),
                    Checkbox::make('is_video')
                        ->label('Video?')
                        ->inline(false),
                    RichEditor::make('description')
                        ->label('Popis média')
                        ->toolbarButtons([
                            'bold', 'italic', 'underline', 'strike', 'link', 'undo', 'redo',
                        ]),
                ]),
            Block::make('blockquote')
                ->label('Citát')
                ->schema([
                    Grid::make(2)->schema([
                        Textarea::make('text')->label('Obsah citátu')->required()->columnSpan(2),
                        TextInput::make('author')->label('Autor')->columnSpan(1),
                        TextInput::make('position')->label('Pozícia / Popis autora')->columnSpan(1),
                    ]),
                ]),
            Block::make('gallery')
                ->label('Galéria')
                ->schema([
                    FileUpload::make('images')
                        ->label('Obrázky')
                        ->disk('public')
                        ->preserveFilenames()
                        ->directory(fn ($record) => self::getMediaDirectory($record))
                        ->multiple()
                        ->panelLayout('grid')
                        ->required()
                        ->imageEditor(),
                ]),
        ];
    }

    public static function getMediaDirectory($record): string
    {
        if (! $record) {
            if (! Storage::disk('public')->exists('temp-builder')) {
                Storage::disk('public')->makeDirectory('temp-builder');
            }

            return 'temp-builder';
        }

        return $record->builder_images_path;
    }
}
