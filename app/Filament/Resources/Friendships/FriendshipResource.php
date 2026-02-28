<?php

namespace App\Filament\Resources\Friendships;

use App\Filament\Resources\Friendships\Pages\CreateFriendship;
use App\Filament\Resources\Friendships\Pages\ListFriendships;
use App\Filament\Resources\Friendships\Pages\ViewFriendship;
use App\Filament\Resources\Friendships\Schemas\FriendshipForm;
use App\Filament\Resources\Friendships\Schemas\FriendshipInfolist;
use App\Filament\Resources\Friendships\Tables\FriendshipsTable;
use App\Models\Friendship;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class FriendshipResource extends Resource
{
    protected static ?string $model = Friendship::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;

    protected static ?string $navigationLabel = 'Дружба';

    protected static string|UnitEnum|null $navigationGroup = 'Социальная сеть';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'дружба';

    protected static ?string $pluralModelLabel = 'дружбы';

    public static function form(Schema $schema): Schema
    {
        return FriendshipForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FriendshipInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FriendshipsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFriendships::route('/'),
            'create' => CreateFriendship::route('/create'),
            'view' => ViewFriendship::route('/{record}'),
        ];
    }
}
