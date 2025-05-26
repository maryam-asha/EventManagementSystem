<?php

namespace App\Services;

use App\Models\EventType;
use Illuminate\Pagination\LengthAwarePaginator;

class EventTypeService
{
    /**
     * Retrieve all event types with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function index(): LengthAwarePaginator
    {
        return EventType::withCount('events')->paginate(10);
    }

    /**
     * Creates and stores a new event type.
     *
     * @param array $data
     * @return EventType
     */
    public function store(array $data): EventType
    {
        return EventType::create($data);
    }

    /**
     * Update an existing event type.
     *
     * @param EventType $eventType
     * @param array $data
     * @return EventType
     */
    public function update(EventType $eventType, array $data): EventType
    {
        $eventType->update($data);
        return $eventType->fresh();
    }

    /**
     * Show a specific event type.
     *
     * @param EventType $eventType
     * @return EventType
     */
    public function show(EventType $eventType): EventType
    {
        return $eventType->load('events');
    }

    /**
     * Delete an event type.
     *
     * @param EventType $eventType
     * @return bool
     */
    public function delete(EventType $eventType): bool
    {
        return $eventType->delete();
    }
}
