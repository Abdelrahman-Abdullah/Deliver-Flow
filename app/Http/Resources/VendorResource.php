<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,

            // Return localized name based on request locale
            'name'        => app()->getLocale() === 'ar'
                                ? $this->name_ar
                                : $this->name_en,

            'description' => app()->getLocale() === 'ar'
                                ? $this->description_ar
                                : $this->description_en,

            'logo'        => $this->logo,
            'address'     => $this->address,
            'latitude'    => $this->latitude,
            'longitude'   => $this->longitude,
            'is_active'   => $this->is_active,
            'is_open'     => $this->is_open,
            'created_at'  => $this->created_at,
        ];
    }
}
