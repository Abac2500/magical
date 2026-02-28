<?php

namespace App\Filament\Resources\Species\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SpeciesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Название вида')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
            ]);
    }
}
