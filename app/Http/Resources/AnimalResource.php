<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Animal */
class AnimalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'nickname' => $this->nickname,
            'species_id' => $this->species_id,
            'species_name' => $this->whenLoaded('species', fn () => $this->species->name),
            'gender' => $this->gender->value,
            'birth_date' => $this->birth_date->toDateString(),
            'best_friend_name' => $this->best_friend_name,
        ];
    }
}
