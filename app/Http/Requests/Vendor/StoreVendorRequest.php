<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // authorization handled by policy
    }

    public function rules(): array
    {
        return [
            'owner_id'        => ['required', 'exists:users,id'],
            'name_en'         => ['required', 'string', 'max:255'],
            'name_ar'         => ['required', 'string', 'max:255'],
            'description_en'  => ['nullable', 'string'],
            'description_ar'  => ['nullable', 'string'],
            'logo'            => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'address'         => ['nullable', 'string'],
            'latitude'        => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'       => ['nullable', 'numeric', 'between:-180,180'],
            'is_active'       => ['boolean'],
            'is_open'         => ['boolean'],
        ];
    }
}