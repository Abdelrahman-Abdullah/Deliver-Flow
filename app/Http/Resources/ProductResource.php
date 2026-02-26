<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isAr = app()->getLocale() === 'ar';

        return [
            'id'          => $this->id,
            'name'        => $isAr ? $this->name_ar        : $this->name_en,
            'description' => $isAr ? $this->description_ar : $this->description_en,
            'price'       => $this->price,
            'image'       => $this->image,
            'is_active'   => $this->is_active,
            'sort_order'  => $this->sort_order,

            // Include category only if loaded
            'category' => $this->whenLoaded(
                'category',
                fn() => new CategoryResource($this->category)
            ),

            // Include vendor only if loaded
            'vendor' => $this->whenLoaded(
                'vendor',
                fn() => new VendorResource($this->vendor)
            ),
        ];
    }
}