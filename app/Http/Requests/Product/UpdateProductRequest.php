<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'     => ['sometimes', 'exists:categories,id'],
            'name_en'         => ['sometimes', 'string', 'max:255'],
            'name_ar'         => ['sometimes', 'string', 'max:255'],
            'description_en'  => ['nullable', 'string'],
            'description_ar'  => ['nullable', 'string'],
            'price'           => ['sometimes', 'numeric', 'min:0'],
            'image'           => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'is_active'       => ['sometimes', 'boolean'],
            'sort_order'      => ['sometimes', 'integer', 'min:0'],
        ];
    }
}