<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class EventFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'event_type_id' => ['required', 'exists:event_types,id'],
            'location_id' => ['required', 'exists:locations,id'],
            'start_date' => ['required', 'date_format:d/m/Y', 'after:now'],
            'end_date' => ['required', 'date_format:d/m/Y', 'after:start_date'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'is_published' => ['boolean'],
            'is_featured' => ['boolean'],
            'status' => ['required', 'string', 'in:draft,published,cancelled,completed'],
            'images.*' => ['nullable', 'image', 'max:2048'], // 2MB max
            'images' => ['nullable', 'array', 'max:5'], // maximum 5 images
        ];

        // Add unique slug validation for create/update
        if ($this->isMethod('POST')) {
            $rules['title'][] = 'unique:events,title';
        } else {
            $rules['title'][] = 'unique:events,title,' . $this->event->id;
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The event title is required.',
            'title.unique' => 'This event title is already taken.',
            'description.required' => 'The event description is required.',
            'event_type_id.required' => 'Please select an event type.',
            'event_type_id.exists' => 'The selected event type is invalid.',
            'location_id.required' => 'Please select a location.',
            'location_id.exists' => 'The selected location is invalid.',
            'start_date.required' => 'The start date is required.',
            'start_date.date_format' => 'The start date must be in the format DD/MM/YYYY.',
            'start_date.after' => 'The start date must be in the future.',
            'end_date.required' => 'The end date is required.',
            'end_date.date_format' => 'The end date must be in the format DD/MM/YYYY.',
            'end_date.after' => 'The end date must be after the start date.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price cannot be negative.',
            'capacity.integer' => 'The capacity must be a whole number.',
            'capacity.min' => 'The capacity must be at least 1.',
            'status.in' => 'The status must be one of: draft, published, cancelled, completed.',
            'images.*.image' => 'The file must be an image.',
            'images.*.max' => 'The image size must not exceed 2MB.',
            'images.max' => 'You can upload maximum 5 images.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // Convert dates from d/m/Y to Y-m-d format
        if ($this->has('start_date')) {
            $this->merge([
                'start_date' => Carbon::createFromFormat('d/m/Y', $this->start_date)->format('Y-m-d')
            ]);
        }

        if ($this->has('end_date')) {
            $this->merge([
                'end_date' => Carbon::createFromFormat('d/m/Y', $this->end_date)->format('Y-m-d')
            ]);
        }
    }
} 