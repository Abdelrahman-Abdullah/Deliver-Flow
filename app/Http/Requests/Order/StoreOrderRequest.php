<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
        return [
            // Which vendor to order from
            'vendor_id'          => ['required', 'exists:vendors,id'],

            // Delivery location
            'delivery_address'   => ['required', 'string'],
            'delivery_latitude'  => ['required', 'numeric', 'between:-90,90'],
            'delivery_longitude' => ['required', 'numeric', 'between:-180,180'],

            // Optional note for the vendor
            'notes'              => ['nullable', 'string', 'max:500'],

            // Cart items — must have at least one
            'items'              => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity'   => ['required', 'integer', 'min:1'],
        ];
    }
}
