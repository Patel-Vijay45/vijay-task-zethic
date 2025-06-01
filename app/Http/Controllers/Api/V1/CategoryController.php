<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function __construct(private CategoryService $categoryService) {}


    /**
     * Get Categories
     *
     * List Categories..
     *
     * @unauthenticated 
     */
    public function index(Request $request)
    {
        ResponseHelper::sendSuccess('Data Fetch Successfully', CategoryResource::collection($this->categoryService->getAllCategorys($request->all()))->response()->getData());
    }

    /**
     * 
     *
     * Store Category for Admin.
     * 
     */
    public function store(CategoryRequest $request)
    {
        ResponseHelper::sendSuccess('Data Fetch Successfully', CategoryResource::make($this->categoryService->createCategory($request->validated()))->response()->getData());
    }

    /**
     *  
     *
     * Update Category for Admin.
     * 
     */
    public function update(CategoryRequest $request, Category $category)
    {
        ResponseHelper::sendSuccess('Data Fetch Successfully', CategoryResource::make($this->categoryService->updateCategory($category, $request->validated()))->response()->getData());
    }

    /**
     * 
     *
     * Delete Category for Admin.
     * 
     */
    public function destroy(Category $category)
    {
        $this->categoryService->deleteCategory($category->id);
        ResponseHelper::sendSuccess('Data Deleted Successfully');
    }
}
