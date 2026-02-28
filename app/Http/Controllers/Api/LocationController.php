<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Models\DriverLocation;
use App\Models\Order;
use App\Services\LocationService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    use ApiResponse;
    public function __construct(public LocationService $locationService){}

    public function update(UpdateLocationRequest $request)
    {
        $driver = $request->user();

        if (!$driver->isDriver()) {
            return $this->forbiddenResponse('Only drivers can update location');
        }
        $order = Order::find($request->order_id);

        if ($driver->id != $order->driver_id) {
            return $this->forbiddenResponse('You can only update location for your assigned order');
        }

        if ($order->status !== Order::STATUS_PICKED_UP) {
            return $this->errorResponse('Location can only be updated for orders that are picked up');
        }



        try {
            $result = $this->locationService->updateDriverLocation(
                $driver, $order,
                $request->latitude,
                $request->longitude
                );
            return $this->successResponse([
            'latitude'    => $result->latitude,
            'longitude'   => $result->longitude,
            'recorded_at' => $result->recorded_at,
        ], 'Location updated successfully');

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), null, 400);
        }
        
    }

    public function currentLocation(Request $request, Order $order)
    {
        $authUser = $request->user();
        if (!$authUser->isSuperAdmin() && $authUser->id != $order->customer_id) {
            return $this->forbiddenResponse('You can only view location for your own orders');
        }

        if (!$order->driver_id) {
            return $this->notFoundResponse('No driver assigned to this order yet');
        }

        try {
            $location = $this->locationService->getLatestLocation($order->driver_id);
            if (!$location) {
                return $this->errorResponse('No location data available yet');
            }

            return $this->successResponse($location, 'Current location retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), null, 400);
        }
    }

    public function locationHistory(Request $request, Order $order)
    {
        $authUser = $request->user();
        if (!$authUser->isSuperAdmin() && $authUser->id != $order->customer_id) {
            return $this->forbiddenResponse('You can only view location for your own orders');
        }

        try {
            $locations = $this->locationService->getLocationHistory($order->id);

            return $this->successResponse($locations, 'Location history retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), null, 400);
        }
    }

        public function distance(Request $request, Order $order)
        {
            $authUser = $request->user();
            if (!$authUser->isSuperAdmin() && $authUser->id != $order->customer_id) {
                return $this->forbiddenResponse('You can only view location for your own orders');
            }
    
            if (!$order->driver_id) {
                return $this->notFoundResponse('No driver assigned to this order yet');
            }
    
            try {
                $latestLocation = $this->locationService->getLatestLocation($order->driver_id);
                if (!$latestLocation) {
                    return $this->errorResponse('No location data available yet');
                }
    
                // Calculate distance using Haversine formula
                $distance = $this->locationService->calculateDistance(
                    driverLatest_latitude: $latestLocation->latitude,
                    driverLatest_longitude: $latestLocation->longitude,
                    order_latitude: $order->delivery_latitude,
                    order_longitude: $order->delivery_longitude

                );
    
                return $this->successResponse([
                    'distance_km' => $distance,
                    'unit' => 'kilometers',
                    'driver_latitude' => $latestLocation->latitude,
                    'driver_longitude' => $latestLocation->longitude,
                    'order_latitude' => (float)$order->delivery_latitude,
                    'order_longitude' => (float)$order->delivery_longitude,
                ], 'Distance calculated successfully');

            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), null, 400);
            }
        }
}
