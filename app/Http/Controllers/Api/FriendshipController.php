<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFriendshipRequest;
use App\Http\Resources\AnimalResource;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class FriendshipController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $animal = $request->user()->animal;
        if (!$animal) {
            return response()->json([
                'message' => 'Сначала создайте профиль зверя.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return AnimalResource::collection(
            $animal->friends()->with('species')->orderBy('name')->get()
        );
    }

    /**
     * @param StoreFriendshipRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreFriendshipRequest $request)
    {
        $payload = $request->validated();

        $animal = $request->user()->animal;
        if (!$animal) {
            return response()->json([
                'message' => 'Сначала создайте профиль зверя.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $friend = Animal::query()->findOrFail($payload['friend_id']);
        if ($animal->is($friend)) {
            return response()->json([
                'message' => 'Нельзя добавить себя в друзья.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($animal->friends()->whereKey($friend->id)->exists()) {
            return response()->json([
                'message' => 'Звери уже являются друзьями.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::transaction(function () use ($animal, $friend): void {
            $animal->friends()->syncWithoutDetaching([$friend->id]);
            $friend->friends()->syncWithoutDetaching([$animal->id]);
        });

        return response()->json([
            'message' => 'Дружба создана.',
        ], Response::HTTP_CREATED);
    }
}
