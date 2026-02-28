<?php
namespace App\Services;

use App\Events\DriverLocationUpdated;
use App\Models\DriverLocation;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Redis;

class LocationService
{
    private function redisKey($driverId)
    {
        return "driver_location:{$driverId}";
    }

    public function updateDriverLocation(
        User $driver, 
        Order $order, 
        $latitude, $longitude
        )
    {
        // Redis: Store Latest Location in Redis [Used For Real-Time Tracking] for 1 hour
        Redis::setex(
            $this->redisKey($driver->id),
            3600, 
            json_encode([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'recorded_at' => now(),
        ]));

        // MySQL: Log location updates to the database for historical tracking
        $location = DriverLocation::create([
            'driver_id' => $driver->id,
            'order_id'  => $order->id,
            'latitude'  => $latitude,
            'longitude' => $longitude,
            'recorded_at' => now(),
        ]);

        // Broadcast event for real-time updates (Map Tracking)
        broadcast(
            new DriverLocationUpdated(
                driverId: $driver->id, 
                orderId: $order->id, 
                latitude: $latitude, 
                longitude: $longitude, 
                recordedAt: now()->toISOString()
            )
        );

        return $location;

    }
}