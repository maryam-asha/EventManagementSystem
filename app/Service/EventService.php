<?php

namespace App\Service;

use App\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EventService
{
    /**
     * Retrieve all events .
     *
     * @param 
     * @return Events
     */
    public function index()
    {
        $query = Event::query()
        ->with(['eventType', 'location', 'organizer'])
        ->withCount('reservations')->paginate(5);
        return $query;
    }


    /**
     * Creates and stores a new event.
     *
     * @param array $data
     * @return Event
     */
    public function store(array $data): Event
    {
        return Event::create($data);
    }

    /**
     * Update an existing event.
     *
     * @param Event $event
     * @param array $data
     * @return Event
     */
    public function update(Event $event, array $data): Event
    {
        $event->update($data);
        return $event;
    }

    /**
     * Show a specific event.
     *
     *
     * @param Event $event
     * @return Event
     */
    public function show(Event $event): Event
    {
        return $event;
    }

    /**
     * Delete a event (soft delete).
     *
     * @param Event $event
     * @return bool
     */
    public function delete(Event $event): bool
    {
        return $event->delete();
    }

    /**
     * Publish an event
     *
     * @param Event $event
     * @return Event
     */
    public function publish(Event $event): Event
    {
        $event->update([
            'is_published' => true,
            'status' => 'published',
        ]);
        
        return $event->fresh();
    }

    /**
     * Feature an event
     *
     * @param Event $event
     * @return Event
     */
    public function feature(Event $event): Event
    {
        $event->update(['is_featured' => true]);
        return $event->fresh();
    }

    /**
     * Store event images
     *
     * @param Event $event
     * @param array $images
     * @return void
     */
    public function storeImages(Event $event, array $images): void
    {
        foreach ($images as $index => $image) {
            $path = $image->store('events', 'public');
            
            $event->images()->create([
                'path' => $path,
                'is_primary' => $index === 0, 
                'order' => $index,
            ]);
        }
    }
}
