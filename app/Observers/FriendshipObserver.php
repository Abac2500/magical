<?php

namespace App\Observers;

use App\Models\Friendship;
use Illuminate\Validation\ValidationException;

class FriendshipObserver
{
    /**
     * @param Friendship $friendship
     * @return void
     */
    public function saving(Friendship $friendship): void
    {
        if ($friendship->animal_id === $friendship->friend_id) {
            throw ValidationException::withMessages([
                'friend_id' => 'Нельзя добавить себя в друзья.',
            ]);
        }
    }

    /**
     * @param Friendship $friendship
     * @return void
     */
    public function created(Friendship $friendship): void
    {
        Friendship::firstOrCreate([
            'animal_id' => $friendship->friend_id,
            'friend_id' => $friendship->animal_id,
        ]);
    }

    /**
     * @param Friendship $friendship
     * @return void
     */
    public function deleted(Friendship $friendship): void
    {
        Friendship::where('animal_id', $friendship->friend_id)
            ->where('friend_id', $friendship->animal_id)
            ->delete();
    }
}
