<?php

namespace App\Filament\Resources\Chats\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ChatInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Название')
                    ->placeholder('-'),
                TextEntry::make('creator.name')
                    ->label('Создатель'),
                TextEntry::make('participants.name')
                    ->label('Участники')
                    ->badge()
                    ->listWithLineBreaks(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
