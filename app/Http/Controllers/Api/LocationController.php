<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationFormRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Services\LocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LocationController extends Controller
{
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
        $this->authorizeResource(Location::class, 'location');
    }

    /**
     * Display a listing of locations.
     */
    public function index(): AnonymousResourceCollection
    {
        $locations = $this->locationService->index();
        return LocationResource::collection($locations);
    }

    /**
     * Store a newly created location.
     */
    public function store(LocationFormRequest $request): JsonResponse
    {
        $location = $this->locationService->store($request->validated());

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            // Ensure we have an array of images
            $imagesArray = is_array($images) ? $images : [$images];
            $this->locationService->storeImages($location, $imagesArray);
        }

        return response()->json([
            'message' => 'Location created successfully',
            'data' => new LocationResource($location)
        ], 201);
    }

    /**
     * Display the specified location.
     */
    public function show(Location $location): LocationResource
    {
        $location = $this->locationService->show($location);
        return new LocationResource($location);
    }

    /**
     * Update the specified location.
     */
    public function update(LocationFormRequest $request, Location $location): JsonResponse
    {
        $location = $this->locationService->update($location, $request->validated());

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            // Ensure we have an array of images
            $imagesArray = is_array($images) ? $images : [$images];
            $this->locationService->storeImages($location, $imagesArray);
        }

        return response()->json([
            'message' => 'Location updated successfully',
            'data' => new LocationResource($location)
        ]);
    }

    /**
     * Remove the specified location.
     */
    public function destroy(Location $location): JsonResponse
    {
        $this->locationService->delete($location);

        return response()->json([
            'message' => 'Location deleted successfully'
        ]);
    }
}
