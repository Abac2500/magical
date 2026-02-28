<?php

namespace App\Filament\Resources\Friendships\Pages;

use App\Filament\Resources\Friendships\FriendshipResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFriendship extends ViewRecord
{
    protected static string $resource = FriendshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
