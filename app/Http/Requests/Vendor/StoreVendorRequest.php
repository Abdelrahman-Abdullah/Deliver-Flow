<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
    return true;
    }

    public function rules(): array
    {
        return [
              'owner_id'       => ['required', 'exists:users,id'],
              'name_en'        => ['required', 'string', 'max:255', 'unique:vendors,name_en'],
              'name_ar'        => ['required', 'string', 'max:255', 'unique:vendors,name_ar'],
              'description_en' => ['nullable', 'string'],
              'description_ar' => ['nullable', 'string'],
              'logo'           => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
              'latitude'       => ['nullable', 'numeric', 'between:-90,90'],
              'longitude'      => ['nullable', 'numeric', 'between:-180,180'],
              'address'        => ['nullable', 'string', 'max:500'],
              'is_active'      => ['sometimes', 'boolean'],
              'is_open'        => ['sometimes', 'boolean'],
        ];
    }
}