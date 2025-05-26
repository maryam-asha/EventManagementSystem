<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LocationFormRequest extends FormRequest
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
        // dd($this);
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string|max:1000',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
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
            'name.required' => 'The location name is required.',
            'address.required' => 'The address is required.',
            'city.required' => 'The city is required.',
            'state.required' => 'The state is required.',
            'country.required' => 'The country is required.',
            'postal_code.required' => 'The postal code is required.',
            'latitude.required' => 'The latitude is required.',
            'latitude.between' => 'The latitude must be between -90 and 90.',
            'longitude.required' => 'The longitude is required.',
            'longitude.between' => 'The longitude must be between -180 and 180.',
            'capacity.required' => 'The capacity is required.',
            'capacity.min' => 'The capacity must be at least 1.',
            'images.*.image' => 'The file must be an image.',
            'images.*.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'images.*.max' => 'The image may not be greater than 2MB.',
        ];
    }
} 