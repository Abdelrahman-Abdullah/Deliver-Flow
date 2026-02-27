<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'status'       => $this->status,
            'total_amount' => $this->total_amount,
            'notes'        => $this->notes,

            // Delivery location
            'delivery' => [
                'address'   => $this->delivery_address,
                'latitude'  => $this->delivery_latitude,
                'longitude' => $this->delivery_longitude,
            ],

            'delivered_at' => $this->delivered_at,
            'created_at'   => $this->created_at,

            // Related resources — only if loaded
            'customer' => $this->whenLoaded('customer', fn() => [
                'id'    => $this->customer->id,
                'name'  => $this->customer->name,
                'phone' => $this->customer->phone,
            ]),

            'vendor' => $this->whenLoaded(
                'vendor',
                fn() => new VendorResource($this->vendor)
            ),

            'driver' => $this->when(
                $this->driver_id && $this->relationLoaded('driver'),
                fn() => [
                    'id'    => $this->driver->id,
                    'name'  => $this->driver->name,
                    'phone' => $this->driver->phone,

                    // Include driver's latest location
                    // for real-time tracking (Step 8)
                    'latest_location' => $this->whenLoaded(
                        'latestDriverLocation',
                        fn() => $this->latestDriverLocation
                            ? [
                                'latitude'    => $this->latestDriverLocation->latitude,
                                'longitude'   => $this->latestDriverLocation->longitude,
                                'recorded_at' => $this->latestDriverLocation->recorded_at,
                            ]
                            : null
                    ),
                ]
            ),

            // Order items with product details
            'items' => $this->whenLoaded(
                'items',
                fn() => OrderItemResource::collection($this->items)
            ),
        ];
    }
}