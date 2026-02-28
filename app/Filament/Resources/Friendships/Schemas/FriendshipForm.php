<?php

namespace App\Filament\Resources\Friendships\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class FriendshipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('animal_id')
                    ->label('Зверь')
                    ->relationship('animal', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->different('friend_id'),
                Select::make('friend_id')
                    ->label('Друг')
                    ->relationship('friend', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->different('animal_id'),
            ]);
    }
}
