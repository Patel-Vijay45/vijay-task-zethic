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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        ResponseHelper::sendSuccess('Data Fetch Successfully', CategoryResource::collection($this->categoryService->getAllCategorys($request->all()))->response()->getData());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        ResponseHelper::sendSuccess('Data Fetch Successfully', CategoryResource::make($this->categoryService->createCategory($request->validated()))->response()->getData());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        ResponseHelper::sendSuccess('Data Fetch Successfully', CategoryResource::make($this->categoryService->updateCategory($category, $request->validated()))->response()->getData());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $this->categoryService->deleteCategory($category->id);
        ResponseHelper::sendSuccess('Data Deleted Successfully');
    }
}
