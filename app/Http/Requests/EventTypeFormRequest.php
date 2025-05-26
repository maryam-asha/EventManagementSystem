<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventTypeFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $eventType = $this->route('eventType');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('event_types')->ignore($eventType),
            ],
            'description' => 'nullable|string|max:1000',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('event_types')->ignore($eventType),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The event type name is required.',
            'name.unique' => 'This event type name is already taken.',
            'description.max' => 'The description cannot exceed 1000 characters.',
            'slug.unique' => 'This slug is already taken.',
        ];
    }
} 