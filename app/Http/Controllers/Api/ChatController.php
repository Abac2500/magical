<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChatRequest;
use App\Http\Resources\ChatResource;
use App\Models\Animal;
use App\Models\Chat;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ChatController extends Controller
{
    /**
     * @param StoreChatRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreChatRequest $request)
    {
        $payload = $request->validated();

        $creator = $request->user()->animal;
        if (!$creator) {
            return response()->json([
                'message' => 'Сначала создайте профиль зверя.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $participantIds = collect($payload['participant_ids'])
            ->map(fn(mixed $id): int => (int)$id)
            ->unique()
            ->reject(fn(int $id): bool => $id === $creator->id)
            ->values();

        if ($participantIds->isEmpty()) {
            return response()->json([
                'message' => 'В чате должен быть хотя бы один друг.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $notFriends = $this->resolveNotFriendIds($creator, $participantIds);
        if ($notFriends->isNotEmpty()) {
            return response()->json([
                'message' => 'Участники чата должны быть друзьями создателя.',
                'invalid_participant_ids' => $notFriends->values(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $chat = DB::transaction(function () use ($creator, $participantIds, $payload): Chat {
            $chat = Chat::query()->create([
                'name' => $payload['name'] ?? null,
                'created_by' => $creator->id,
            ]);

            $chat->participants()->sync(
                $participantIds->push($creator->id)->all()
            );

            return $chat->load('participants');
        });

        return (new ChatResource($chat))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @param Animal $creator
     * @param Collection $participantIds
     * @return Collection
     */
    private function resolveNotFriendIds(Animal $creator, Collection $participantIds): Collection
    {
        $friendIds = $creator->friends()->pluck('animals.id');

        return $participantIds->diff($friendIds);
    }
}
