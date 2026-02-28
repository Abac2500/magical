<?php

namespace App\Observers;

use App\Models\Chat;

class ChatObserver
{
    /**
     * @param Chat $chat
     * @return void
     */
    public function created(Chat $chat): void
    {
        $chat->participants()->syncWithoutDetaching([$chat->created_by]);
    }
}
