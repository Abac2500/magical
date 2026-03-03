<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSpeciesRequest;
use App\Http\Resources\SpeciesResource;
use App\Models\Species;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class SpeciesController extends Controller
{
    private const SPECIES_CACHE_KEY = 'species:list:v1';

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $species = Cache::rememberForever(self::SPECIES_CACHE_KEY, static fn () => Species::orderBy('name')
            ->get());

        return SpeciesResource::collection(
            $species
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSpeciesRequest $request)
    {
        $species = Species::create($request->validated());
        Cache::forget(self::SPECIES_CACHE_KEY);

        return (new SpeciesResource($species))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
