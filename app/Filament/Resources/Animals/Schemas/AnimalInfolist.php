<?php

namespace App\Filament\Resources\Animals\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AnimalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.email')
                    ->label('Владелец')
                    ->placeholder('-'),
                TextEntry::make('name')
                    ->label('Имя'),
                TextEntry::make('nickname')
                    ->label('Прозвище')
                    ->placeholder('-'),
                TextEntry::make('species.name')
                    ->label('Вид'),
                TextEntry::make('gender')
                    ->label('Пол')
                    ->badge(),
                TextEntry::make('birth_date')
                    ->label('Дата рождения')
                    ->date(),
                TextEntry::make('best_friend_name')
                    ->label('Имя лучшего друга'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
