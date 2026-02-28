<?php

namespace App\Filament\Resources\Chats\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ChatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Название')
                    ->maxLength(255),
                Select::make('created_by')
                    ->label('Создатель')
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live(),
                Select::make('participants')
                    ->label('Участники')
                    ->relationship('participants', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}
