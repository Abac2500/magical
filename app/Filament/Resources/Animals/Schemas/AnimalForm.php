<?php

namespace App\Filament\Resources\Animals\Schemas;

use App\Enums\Gender;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AnimalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Владелец (пользователь)')
                    ->relationship('user', 'email')
                    ->searchable()
                    ->preload()
                    ->unique(ignoreRecord: true),
                TextInput::make('name')
                    ->label('Имя')
                    ->required()
                    ->maxLength(255),
                TextInput::make('nickname')
                    ->label('Прозвище')
                    ->maxLength(255),
                Select::make('species_id')
                    ->label('Вид')
                    ->relationship('species', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('gender')
                    ->label('Пол')
                    ->options([
                        Gender::Male->value => Gender::Male->value,
                        Gender::Female->value => Gender::Female->value,
                    ])
                    ->required(),
                DatePicker::make('birth_date')
                    ->label('Дата рождения')
                    ->required()
                    ->native(false),
                TextInput::make('best_friend_name')
                    ->label('Имя лучшего друга')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
