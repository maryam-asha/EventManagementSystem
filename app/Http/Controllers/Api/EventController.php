<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventFormRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\User;
use App\Services\EventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{
    protected $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
        $this->authorizeResource(Event::class, 'event');
    }

    public function index(): JsonResponse
    {
        $this->authorize('index', Event::class);
        $events = $this->eventService->index();
        return response()->json($events);
    }

    public function store(EventFormRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            Log::info('Validated data:', $validatedData);

            $event = $this->eventService->store($validatedData + ['user_id' => auth()->id()]);

            if ($request->hasFile('images')) {
                $this->eventService->storeImages($event, $request->file('images'));
            }

            return response()->json([
                'message' => 'Event created successfully',
                'data' => new EventResource($event->load(['eventType', 'location', 'organizer', 'images']))
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            Log::error('Event creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Failed to create event',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Event $event): JsonResponse
    {
        $event->load(['eventType', 'location', 'organizer', 'images'])
            ->loadCount('reservations');

        return response()->json(new EventResource($event));
    }

    public function update(EventFormRequest $request, Event $event): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $event = $this->eventService->update($event, $validatedData);

            if ($request->hasFile('images')) {
                $this->eventService->storeImages($event, $request->file('images'));
            }

            return response()->json([
                'message' => 'Event updated successfully',
                'data' => new EventResource($event->load(['eventType', 'location', 'organizer', 'images']))
            ]);

        } catch (\Exception $e) {
            Log::error('Event update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Failed to update event',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Event $event): JsonResponse
    {
        try {
            $this->eventService->delete($event);
            return response()->json([
                'message' => 'Event deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete event',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function publish(Event $event): JsonResponse
    {
        $this->authorize('publish', $event);

        try {
            $event = $this->eventService->publish($event);
            return response()->json([
                'message' => 'Event published successfully',
                'data' => new EventResource($event)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to publish event',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function feature(Event $event): JsonResponse
    {
        $this->authorize('feature', $event);

        try {
            $event = $this->eventService->feature($event);
            return response()->json([
                'message' => 'Event featured successfully',
                'data' => new EventResource($event)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to feature event',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
