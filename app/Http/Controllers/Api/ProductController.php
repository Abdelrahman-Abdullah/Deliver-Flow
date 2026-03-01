<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Vendor;
use App\Traits\ApiResponse;

class ProductController extends Controller
{
    use ApiResponse;

    // GET /api/vendors/{vendor}/products — public
    public function index(Vendor $vendor)
    {
        $products = $vendor->activeProducts()
            ->with('category')
            ->orderBy('sort_order')
            ->paginate(15);

        return $this->successResponse(
            ProductResource::collection($products)
        );
    }

    // GET /api/products/{product} — public
    public function show(Product $product)
    {
        $product->load(['vendor', 'category']);
        return $this->successResponse(new ProductResource($product));
    }

    public function store(StoreProductRequest $request)
    {
        $this->authorize('create', Product::class);
        
        // Get the vendor that belongs to the authenticated user
        // A vendor can only add products to THEIR OWN store
        $vendor = $request->user()->vendor;
        if (!$vendor) {
            return $this->errorResponse('You must have a vendor store to add products.', 403);
        }

        $data = $request->validated();
        $data['vendor_id'] = $vendor->id;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('products', 'public');
        }

        $product = Product::create($data);
        $product->load(['vendor', 'category']);
        return $this->createdResponse(new ProductResource($product), 'Product created successfully.');

   
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('products', 'public');
        }

        $product->update($data);
        $product->load(['vendor', 'category']);
        return $this->successResponse(new ProductResource($product), 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $product->delete();
        return $this->deletedResponse( 'Product deleted successfully.');
    }

}