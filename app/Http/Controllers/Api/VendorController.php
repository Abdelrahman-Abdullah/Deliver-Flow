<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\StoreVendorRequest;
use App\Http\Requests\Vendor\UpdateVendorRequest;
use App\Http\Resources\VendorResource;
use App\Models\Vendor;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class VendorController extends Controller
{
    use ApiResponse;

     // GET /api/vendors — public
    public function index(): JsonResponse
    {
        $vendors = Vendor::where('is_active', true)
            ->with('owner')
            ->paginate(10);
        return $this->successResponse(
            VendorResource::collection($vendors)
        );
    }

    // GET /api/vendors/{vendor} — public
    public function show(Vendor $vendor): JsonResponse
    {
        $vendor->load('activeProducts');

        return $this->successResponse(
            new VendorResource($vendor)
        );
    }

    // POST /api/vendors — super_admin only
    public function store(StoreVendorRequest $request): JsonResponse
    {
        $this->authorize('create', Vendor::class);

        $data = $request->validated();
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        } 

        $vendor = Vendor::create($data);

        return $this->createdResponse(
            new VendorResource($vendor),
            'Vendor created successfully.',
        );
    }

     // PUT /api/vendors/{vendor} — super_admin or vendor owner
    public function update(UpdateVendorRequest $request, Vendor $vendor): JsonResponse
    {$this->authorize('update', $vendor);
        

        $data = $request->validated();
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $vendor->update($data);

        return $this->successResponse(
            new VendorResource($vendor),
            'Vendor updated successfully.'
        );
    }

        // DELETE /api/vendors/{vendor} — super_admin only
    public function destroy(Vendor $vendor): JsonResponse
    {
        $this->authorize('delete', $vendor);

        $vendor->delete();

        return $this->deletedResponse('Vendor deleted successfully');
    }


}
