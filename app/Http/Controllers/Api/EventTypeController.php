<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventTypeFormRequest;
use App\Http\Resources\EventTypeResource;
use App\Models\EventType;
use App\Services\EventTypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventTypeController extends Controller
{
    protected $eventTypeService;

    public function __construct(EventTypeService $eventTypeService)
    {
        $this->eventTypeService = $eventTypeService;
        $this->authorizeResource(EventType::class, 'eventType');
    }

    /**
     * Display a listing of event types.
     */
    public function index()
    {
        $eventTypes = $this->eventTypeService->index();
        return EventTypeResource::collection($eventTypes);
    }

    /**
     * Store a newly created event type.
     */
    public function store(EventTypeFormRequest $request): JsonResponse
    {
        $eventType = $this->eventTypeService->store($request->validated());

        return response()->json([
            'message' => 'Event type created successfully',
            'data' => new EventTypeResource($eventType)
        ], 201);
    }

    /**
     * Display the specified event type.
     */
    public function show(EventType $eventType): EventTypeResource
    {
        $eventType = $this->eventTypeService->show($eventType);
        return new EventTypeResource($eventType);
    }

    /**
     * Update the specified event type.
     */
    public function update(EventTypeFormRequest $request, EventType $eventType): JsonResponse
    {
        $eventType = $this->eventTypeService->update($eventType, $request->validated());

        return response()->json([
            'message' => 'Event type updated successfully',
            'data' => new EventTypeResource($eventType)
        ]);
    }

    /**
     * Remove the specified event type.
     */
    public function destroy(EventType $eventType): JsonResponse
    {
        $this->eventTypeService->delete($eventType);

        return response()->json([
            'message' => 'Event type deleted successfully'
        ]);
    }
}
