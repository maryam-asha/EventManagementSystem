<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'event' => new EventResource($this->whenLoaded('event')),
            'user' => new UserResource($this->whenLoaded('user')),
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'notes' => $this->notes,
            'is_cancelled' => $this->is_cancelled,
            'is_confirmed' => $this->is_confirmed,
            'is_completed' => $this->is_completed,
            'cancelled_at' => $this->cancelled_at,
            'cancellation_reason' => $this->cancellation_reason,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 