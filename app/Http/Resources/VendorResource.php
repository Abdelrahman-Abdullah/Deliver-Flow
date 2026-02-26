<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isAr = app()->getLocale() === 'ar';

        return [
            'id'          => $this->id,
            'name'        => $isAr ? $this->name_ar        : $this->name_en,
            'description' => $isAr ? $this->description_ar : $this->description_en,
            'logo'        => $this->logo,
            'address'     => $this->address,
            'latitude'    => $this->latitude,
            'longitude'   => $this->longitude,
            'is_active'   => $this->is_active,
            'is_open'     => $this->is_open,
            'created_at'  => $this->created_at,

            // owner only visible to super_admin
            'owner' => $this->when(
                auth()->check() && auth()->user()->isSuperAdmin() && $this->relationLoaded('owner'),
                fn() => [
                    'id'    => $this->owner->id,
                    'name'  => $this->owner->name,
                    'email' => $this->owner->email,
                    'phone' => $this->owner->phone,
                ]
            ),

            // Include products only if loaded
            // loaded in show() via load('activeProducts')
            'products' => $this->whenLoaded(
                'activeProducts',
                fn() => ProductResource::collection($this->activeProducts)
            ),
        ];
    }
}