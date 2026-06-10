<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHotelRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // get the hotel from Route Model Binding
        $hotel = $this->route('hotel');

        return [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'city' => [
                'required',
                'string',
                'max:255'
            ],
            'address' => [
                'required',
                'string',
                'max:500'
            ],
            'nit' => [
                'required',
                'string',
                'max:100',
                // unique but ignore the current hotel
                Rule::unique('hotels', 'nit')->ignore($hotel->id)
            ],
            'total_rooms' => [
                'required',
                'integer',
                'min:1'
            ],
            // There must be at least one configuration
            'configurations' => [
                'required',
                'array',
                'min:1'
            ],
            'configurations.*.room_type_id' => [
                'required',
                'exists:room_types,id'
            ],
             'configurations.*.accommodation_id' => [
                'required',
                'exists:accommodations,id'
            ],
            'configurations.*.quantity' => [
                'required',
                'integer',
                'min:1'
            ]
        ];
    }

    /**
     * Messages customization
     */
    public function messages(): array
    {
        return [
            'configurations.required' => 'Debe proporcionar al menos una configuración de habitación.',
            'configurations.*.room_type_id.exists' => 'El tipo de habitación seleccionado no existe.',
            'configurations.*.accommodation_id.exists' => 'La acomodación seleccionada no existe.',
        ];
    }
}
