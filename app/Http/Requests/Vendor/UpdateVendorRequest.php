<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_en'         => ['sometimes', 'string', 'max:255'],
            'name_ar'         => ['sometimes', 'string', 'max:255'],
            'description_en'  => ['nullable', 'string'],
            'description_ar'  => ['nullable', 'string'],
            'logo'            => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'address'         => ['nullable', 'string'],
            'latitude'        => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'       => ['nullable', 'numeric', 'between:-180,180'],
            'is_active'       => ['sometimes', 'boolean'],
            'is_open'         => ['sometimes', 'boolean'],
        ];
    }
}