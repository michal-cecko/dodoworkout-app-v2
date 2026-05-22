<?php

namespace App\Filament\Resources\PostTags;

use App\Filament\Resources\PostTags\Pages\CreatePostTag;
use App\Filament\Resources\PostTags\Pages\EditPostTag;
use App\Filament\Resources\PostTags\Pages\ListPostTags;
use App\Filament\Resources\PostTags\RelationManagers\PostsRelationManager;
use App\Filament\Resources\PostTags\Schemas\PostTagForm;
use App\Filament\Resources\PostTags\Tables\PostTagsTable;
use App\Models\PostTag;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PostTagResource extends Resource
{
    protected static ?string $model = PostTag::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $modelLabel = 'Tag článku';

    protected static ?string $pluralModelLabel = 'Tagy článkov';

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static UnitEnum|string|null $navigationGroup = 'Obsah';

    protected static ?int $navigationSort = 11;

    public static function form(Schema $schema): Schema
    {
        return PostTagForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostTagsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PostsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPostTags::route('/'),
            'create' => CreatePostTag::route('/create'),
            'edit' => EditPostTag::route('/{record}/edit'),
        ];
    }
}
