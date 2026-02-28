<?php

namespace App\Filament\Resources\Animals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AnimalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.email')
                    ->label('Владелец')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('name')
                    ->label('Имя')
                    ->searchable(),
                TextColumn::make('nickname')
                    ->label('Прозвище')
                    ->searchable(),
                TextColumn::make('species.name')
                    ->label('Вид')
                    ->searchable(),
                TextColumn::make('gender')
                    ->label('Пол')
                    ->badge()
                    ->searchable(),
                TextColumn::make('birth_date')
                    ->label('Дата рождения')
                    ->date()
                    ->sortable(),
                TextColumn::make('best_friend_name')
                    ->label('Имя лучшего друга')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
