<?php

namespace App\Services;

use App\Models\Location;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

class LocationService
{
    /**
     * Retrieve all locations with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function index(int $perPage = 10): LengthAwarePaginator
    {
        return Location::withCount('events')
            ->with('images')
            ->latest()
            
            ->paginate($perPage);
    }

    /**
     * Creates and stores a new location.
     *
     * @param array $data
     * @return Location
     */
    public function store(array $data): Location
    {
        return Location::create($data);
    }

    /**
     * Update an existing location.
     *
     * @param Location $location
     * @param array $data
     * @return Location
     */
    public function update(Location $location, array $data): Location
    {
        $location->update($data);
        return $location->fresh(['images']);
    }

    /**
     * Show a specific location.
     *
     * @param Location $location
     * @return Location
     */
    public function show(Location $location): Location
    {
        return $location->load(['events', 'images']);
    }

    /**
     * Delete a location.
     *
     * @param Location $location
     * @return bool
     */
    public function delete(Location $location): bool
    {
        return $location->delete();
    }

    /**
     * Store location images
     *
     * @param Location $location
     * @param array<UploadedFile> $images
     * @return void
     */
    public function storeImages(Location $location, array $images): void
    {
        foreach ($images as $index => $image) {
            if (!$image instanceof UploadedFile) {
                continue;
            }

            $path = $image->store('locations', 'public');
            $filename = $image->getClientOriginalName();
            $mimeType = $image->getMimeType();
            $size = $image->getSize();

            $location->images()->create([
                'path' => $path,
                'filename' => $filename,
                'mime_type' => $mimeType,
                'size' => $size,
                'alt_text' => $filename, 
                'is_primary' => $index === 0,
                'order' => $index,
            ]);
        }
    }
}
