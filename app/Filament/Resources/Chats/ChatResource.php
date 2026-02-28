<?php

namespace App\Filament\Resources\Chats;

use App\Filament\Resources\Chats\Pages\CreateChat;
use App\Filament\Resources\Chats\Pages\EditChat;
use App\Filament\Resources\Chats\Pages\ListChats;
use App\Filament\Resources\Chats\Pages\ViewChat;
use App\Filament\Resources\Chats\Schemas\ChatForm;
use App\Filament\Resources\Chats\Schemas\ChatInfolist;
use App\Filament\Resources\Chats\Tables\ChatsTable;
use App\Models\Chat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ChatResource extends Resource
{
    protected static ?string $model = Chat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'Чаты';

    protected static string|UnitEnum|null $navigationGroup = 'Социальная сеть';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'чат';

    protected static ?string $pluralModelLabel = 'чаты';

    public static function form(Schema $schema): Schema
    {
        return ChatForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ChatInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChatsTable::configure($table);
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
            'index' => ListChats::route('/'),
            'create' => CreateChat::route('/create'),
            'view' => ViewChat::route('/{record}'),
            'edit' => EditChat::route('/{record}/edit'),
        ];
    }
}
