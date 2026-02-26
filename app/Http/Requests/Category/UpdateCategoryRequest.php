<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // 'ignore' current record on unique check so vendor can
        // update other fields without triggering unique violation on same name
        $categoryId = $this->route('category')?->id;

        return [
            'name_en'    => ['sometimes', 'string', 'max:255', 'unique:categories,name_en,' . $categoryId],
            'name_ar'    => ['sometimes', 'string', 'max:255', 'unique:categories,name_ar,' . $categoryId],
            'image'      => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'is_active'  => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}