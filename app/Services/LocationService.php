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

    public function getLatestLocation($driverId)
    {
        $cached = Redis::get($this->redisKey($driverId));
        return $cached ? json_decode($cached) : null;
  
    }
    
    public function getLocationHistory($orderId)
    {
        return DriverLocation::where('order_id', $orderId)
            ->orderBy('recorded_at', 'desc')
            ->get(['latitude', 'longitude', 'recorded_at']);
    }

     // -----------------------------------------------
    // Calculate distance between two coordinates (km)
    // Uses the Haversine formula
    // -----------------------------------------------
    public function calculateDistance(
        float $driverLatest_latitude,
        float $driverLatest_longitude,
        float $order_latitude,
        float $order_longitude
    ): float {
        $earthRadius = 6371; // km

        $latDelta = deg2rad($order_latitude - $driverLatest_latitude);
        $lngDelta = deg2rad($order_longitude - $driverLatest_longitude);

        $a = sin($latDelta / 2) * sin($latDelta / 2)
           + cos(deg2rad($driverLatest_latitude)) * cos(deg2rad($order_latitude))
           * sin($lngDelta / 2) * sin($lngDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2); // returns km rounded to 2 decimals
    }
}