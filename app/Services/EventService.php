<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class EventService
{
    /**
     * Retrieve all events.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index()
    {
        return Event::query()
            ->with(['eventType', 'location', 'organizer'])
            ->withCount('reservations')
            ->latest()
            ->paginate(5);
    }

    /**
     * Creates and stores a new event.
     *
     * @param array $data
     * @return Event
     * @throws \Exception
     */
    public function store(array $data): Event
    {
        try {
            DB::beginTransaction();

            Log::info('Creating event with data:', $data);

            $event = Event::create([
                'title' => $data['title'],
                'slug' => $data['slug'] ?? str()->slug($data['title']),
                'description' => $data['description'],
                'event_type_id' => $data['event_type_id'],
                'location_id' => $data['location_id'],
                'user_id' => $data['user_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'price' => $data['price'] ?? 0,
                'capacity' => $data['capacity'] ?? null,
                'is_published' => $data['is_published'] ?? false,
                'is_featured' => $data['is_featured'] ?? false,
                'status' => $data['status'] ?? 'draft',
            ]);

            DB::commit();

            Log::info('Event created successfully:', ['event_id' => $event->id]);

            return $event->fresh(['eventType', 'location', 'organizer']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create event:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing event.
     *
     * @param Event $event
     * @param array $data
     * @return Event
     * @throws \Exception
     */
    public function update(Event $event, array $data): Event
    {
        try {
            DB::beginTransaction();

            $event->update([
                'title' => $data['title'],
                'slug' => $data['slug'] ?? str()->slug($data['title']),
                'description' => $data['description'],
                'event_type_id' => $data['event_type_id'],
                'location_id' => $data['location_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'price' => $data['price'] ?? $event->price,
                'capacity' => $data['capacity'] ?? $event->capacity,
                'is_published' => $data['is_published'] ?? $event->is_published,
                'is_featured' => $data['is_featured'] ?? $event->is_featured,
                'status' => $data['status'] ?? $event->status,
            ]);

            DB::commit();

            return $event->fresh(['eventType', 'location', 'organizer']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update event:', [
                'event_id' => $event->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Show a specific event.
     *
     * @param Event $event
     * @return Event
     */
    public function show(Event $event): Event
    {
        return $event->load(['eventType', 'location', 'organizer', 'images'])
            ->loadCount('reservations');
    }

    /**
     * Delete an event (soft delete).
     *
     * @param Event $event
     * @return bool
     * @throws \Exception
     */
    public function delete(Event $event): bool
    {
        try {
            DB::beginTransaction();
            $result = $event->delete();
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete event:', [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Publish an event
     *
     * @param Event $event
     * @return Event
     * @throws \Exception
     */
    public function publish(Event $event): Event
    {
        try {
            DB::beginTransaction();
            
            $event->update([
                'is_published' => true,
                'status' => 'published',
            ]);

            DB::commit();
            return $event->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to publish event:', [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Feature an event
     *
     * @param Event $event
     * @return Event
     * @throws \Exception
     */
    public function feature(Event $event): Event
    {
        try {
            DB::beginTransaction();
            
            $event->update(['is_featured' => true]);
            
            DB::commit();
            return $event->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to feature event:', [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Store event images
     *
     * @param Event $event
     * @param array $images
     * @return void
     * @throws \Exception
     */
    public function storeImages(Event $event, array $images): void
    {
        try {
            DB::beginTransaction();

            foreach ($images as $index => $image) {
                $path = $image->store('events', 'public');

                $event->images()->create([
                    'path' => $path,
                    'filename' => $image->getClientOriginalName(),
                    'mime_type' => $image->getMimeType(),
                    'size' => $image->getSize(),
                    'is_primary' => $index === 0,
                    'order' => $index,
                ]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to store event images:', [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
