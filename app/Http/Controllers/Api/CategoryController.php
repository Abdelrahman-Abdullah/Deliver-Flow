<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use ApiResponse;

    // GET /api/categories — public
    public function index(): JsonResponse
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return $this->successResponse(
            CategoryResource::collection($categories)
        );
    }

    // POST /api/categories — super_admin only
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $this->authorize('create', Category::class);

        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('categories', 'public');
        }

        $category = Category::create($data);

        return $this->createdResponse(
            new CategoryResource($category),
            'Category created successfully'
        );
    }
    
    // GET /api/categories/{category} — public
    public function show(Category $category): JsonResponse
    {
        $category->load('products');
        return $this->successResponse(
            new CategoryResource($category)
        );  
    }


    // PUT /api/categories/{category} — super_admin only
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $this->authorize('update', $category);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('categories', 'public');
        }

        $category->update($data);

        return $this->successResponse(
            new CategoryResource($category),
            'Category updated successfully'
        );
    }

    // DELETE /api/categories/{category} — super_admin only
    public function destroy(Category $category): JsonResponse
    {
        $this->authorize('delete', $category);

        $category->delete();

        return $this->deletedResponse('Category deleted successfully');
    }
}