<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Chat */
class ChatResource extends JsonResource
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
            'name' => $this->name,
            'created_by' => $this->created_by,
            'participant_ids' => $this->whenLoaded(
                'participants',
                fn() => $this->participants->pluck('id')->values()
            ),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
