<?php

namespace App\Filament\Resources\Species\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SpeciesInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Название вида'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
