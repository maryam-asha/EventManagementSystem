<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'price' => $this->price,
            'capacity' => $this->capacity,
            'remaining_capacity' => $this->remaining_capacity,
            'is_published' => $this->is_published,
            'is_featured' => $this->is_featured,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // relation
            'event_type' => new EventTypeResource($this->whenLoaded('eventType')),
            'location' => new LocationResource($this->whenLoaded('location')),
            'organizer' => new UserResource($this->whenLoaded('organizer')),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'reservations_count' => $this->whenCounted('reservations'),
        ];
    }
}
