<?php

namespace App\Filament\Resources\Messages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('chat_id')
                    ->label('Чат')
                    ->relationship('chat', 'id')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('sender_id')
                    ->label('Отправитель')
                    ->relationship('sender', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('body')
                    ->label('Сообщение')
                    ->required()
                    ->maxLength(128),
            ]);
    }
}
