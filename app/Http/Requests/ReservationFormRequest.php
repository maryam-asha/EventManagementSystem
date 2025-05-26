<?php

namespace App\Http\Requests;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservationFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'event_id' => ['required', 'exists:events,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ];

        // Add validation for event availability and capacity
        if ($this->isMethod('POST')) {
            $rules['event_id'][] = function ($attribute, $value, $fail) {
                $event = Event::find($value);
                
                if (!$event) {
                    return;
                }

                if ($event->isFullyBooked) {
                    $fail('This event is fully booked.');
                }

                if ($event->start_date < now()) {
                    $fail('This event has already started.');
                }

                if ($event->status !== 'published') {
                    $fail('This event is not available for reservations.');
                }
            };

            $rules['quantity'][] = function ($attribute, $value, $fail) {
                $event = Event::find($this->input('event_id'));
                
                if (!$event) {
                    return;
                }

                if ($event->remaining_capacity < $value) {
                    $fail("Only {$event->remaining_capacity} spots are available.");
                }
            };
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'event_id.required' => 'Please select an event.',
            'event_id.exists' => 'The selected event is invalid.',
            'quantity.required' => 'Please specify the number of tickets.',
            'quantity.integer' => 'The quantity must be a whole number.',
            'quantity.min' => 'You must reserve at least 1 ticket.',
        ];
    }
} 