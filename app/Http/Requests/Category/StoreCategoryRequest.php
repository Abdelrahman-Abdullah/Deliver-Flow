<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_en'    => ['required', 'string', 'max:255', 'unique:categories,name_en'],
            'name_ar'    => ['required', 'string', 'max:255', 'unique:categories,name_ar'],
            'image'      => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'is_active'  => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ];
    }
}