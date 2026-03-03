<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnimalResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MyAnimalController extends Controller
{
    /**
     * @return AnimalResource|\Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $animal = $request->user()->animal()?->with('species')->first();

        if (! $animal) {
            return response()->json([
                'message' => 'Профиль зверя не создан.',
            ], Response::HTTP_NOT_FOUND);
        }

        return new AnimalResource($animal);
    }
}
