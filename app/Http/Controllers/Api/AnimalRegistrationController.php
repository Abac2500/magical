<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnimalRequest;
use App\Http\Resources\AnimalResource;
use App\Models\Animal;
use Symfony\Component\HttpFoundation\Response;

class AnimalRegistrationController extends Controller
{
    /**
     * @param StoreAnimalRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreAnimalRequest $request)
    {
        if ($request->user()->animal()->exists()) {
            return response()->json([
                'message' => 'У пользователя уже есть профиль зверя.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $animal = Animal::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ])
            ->load('species');

        return (new AnimalResource($animal))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
