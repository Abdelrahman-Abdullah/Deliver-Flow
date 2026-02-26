<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'     => ['required', 'exists:categories,id'],
            'name_en'         => ['required', 'string', 'max:255'],
            'name_ar'         => ['required', 'string', 'max:255'],
            'description_en'  => ['nullable', 'string'],
            'description_ar'  => ['nullable', 'string'],
            'price'           => ['required', 'numeric', 'min:0'],
            'image'           => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'is_active'       => ['boolean'],
            'sort_order'      => ['integer', 'min:0'],
        ];
    }
}