<?php

namespace App\Filament\Resources\Friendships\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FriendshipInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('animal.name')
                    ->label('Зверь'),
                TextEntry::make('friend.name')
                    ->label('Друг'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
