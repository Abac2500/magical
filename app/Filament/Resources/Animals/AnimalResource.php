<?php

namespace App\Filament\Resources\Animals;

use App\Filament\Resources\Animals\Pages\CreateAnimal;
use App\Filament\Resources\Animals\Pages\EditAnimal;
use App\Filament\Resources\Animals\Pages\ListAnimals;
use App\Filament\Resources\Animals\Pages\ViewAnimal;
use App\Filament\Resources\Animals\Schemas\AnimalForm;
use App\Filament\Resources\Animals\Schemas\AnimalInfolist;
use App\Filament\Resources\Animals\Tables\AnimalsTable;
use App\Models\Animal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AnimalResource extends Resource
{
    protected static ?string $model = Animal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Звери';

    protected static string|UnitEnum|null $navigationGroup = 'Социальная сеть';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'зверь';

    protected static ?string $pluralModelLabel = 'звери';

    public static function form(Schema $schema): Schema
    {
        return AnimalForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AnimalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AnimalsTable::configure($table);
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
            'index' => ListAnimals::route('/'),
            'create' => CreateAnimal::route('/create'),
            'view' => ViewAnimal::route('/{record}'),
            'edit' => EditAnimal::route('/{record}/edit'),
        ];
    }
}
