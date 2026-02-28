<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSpeciesRequest;
use App\Http\Resources\SpeciesResource;
use App\Models\Species;
use Symfony\Component\HttpFoundation\Response;

class SpeciesController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return SpeciesResource::collection(
            Species::orderBy('name')->get()
        );
    }

    /**
     * @param StoreSpeciesRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSpeciesRequest $request)
    {
        $species = Species::create($request->validated());

        return (new SpeciesResource($species))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
