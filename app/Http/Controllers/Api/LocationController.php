<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Models\Order;
use App\Services\LocationService;
use App\Traits\ApiResponse;

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
            return $this->forbiddenResponse('Location can only be updated for orders that are picked up');
        }



        try {
            $result = $this->locationService->updateDriverLocation(
                $driver, $order,
                $request->latitude,
                $request->longitude
                );
            return $this->successResponse($result, 'Location updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), null, 400);
        }
        
    }
}
