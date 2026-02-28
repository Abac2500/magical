<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnimalResource;
use App\Services\FriendRecommendationService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecommendationController extends Controller
{
    /**
     * @param FriendRecommendationService $recommendationService
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(FriendRecommendationService $recommendationService, Request $request)
    {
        $animal = $request->user()->animal;
        if (!$animal) {
            return response()->json([
                'message' => 'Сначала создайте профиль зверя.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return AnimalResource::collection(
            $recommendationService->for($animal, 10)
        );
    }
}
