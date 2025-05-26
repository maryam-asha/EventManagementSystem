<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationFormRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
        $this->authorizeResource(Reservation::class, 'reservation');
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only([
            'status',
            'event',
            'user',
            'upcoming',
            'past',
            'sort_by',
            'sort_direction'
        ]);

        $reservations = $this->reservationService->index($filters, $request->input('per_page', 15));
        return ReservationResource::collection($reservations);
    }

    public function store(ReservationFormRequest $request): JsonResponse
    {
        $reservation = $this->reservationService->store($request->validated());

        return response()->json([
            'message' => 'Reservation created successfully',
            'data' => new ReservationResource($reservation)
        ], 201);
    }

    public function show(Reservation $reservation): ReservationResource
    {
        $reservation = $this->reservationService->show($reservation);
        return new ReservationResource($reservation);
    }

    public function update(ReservationFormRequest $request, Reservation $reservation): JsonResponse
    {
        $reservation = $this->reservationService->update($reservation, $request->validated());

        return response()->json([
            'message' => 'Reservation updated successfully',
            'data' => new ReservationResource($reservation)
        ]);
    }

    public function destroy(Reservation $reservation): JsonResponse
    {
        $this->reservationService->delete($reservation);

        return response()->json([
            'message' => 'Reservation deleted successfully'
        ]);
    }

    public function cancel(Request $request, Reservation $reservation): JsonResponse
    {
        $this->authorize('cancel', $reservation);

        $this->reservationService->cancel($reservation, $request->input('reason'));

        return response()->json([
            'message' => 'Reservation cancelled successfully',
            'data' => new ReservationResource($reservation)
        ]);
    }

    public function confirm(Reservation $reservation): JsonResponse
    {
        $this->authorize('confirm', $reservation);

        $this->reservationService->confirm($reservation);

        return response()->json([
            'message' => 'Reservation confirmed successfully',
            'data' => new ReservationResource($reservation)
        ]);
    }

    public function complete(Reservation $reservation): JsonResponse
    {
        $this->authorize('confirm', $reservation);

        $this->reservationService->complete($reservation);

        return response()->json([
            'message' => 'Reservation completed successfully',
            'data' => new ReservationResource($reservation)
        ]);
    }
} 