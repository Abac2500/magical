<?php

namespace App\Models;

use App\Observers\FriendshipObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([FriendshipObserver::class])]
class Friendship extends Model
{
    /**
     * The attributes that are mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'animal_id',
        'friend_id',
    ];

    /**
     * @return BelongsTo
     */
    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class, 'animal_id');
    }

    /**
     * @return BelongsTo
     */
    public function friend(): BelongsTo
    {
        return $this->belongsTo(Animal::class, 'friend_id');
    }
}
