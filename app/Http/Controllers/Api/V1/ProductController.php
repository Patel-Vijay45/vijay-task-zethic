<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{


    public function __construct(private ProductService $productService) {}


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        ResponseHelper::sendSuccess('Data Fetch Successfully', ProductResource::collection($this->productService->getAllProducts($request->all(), with: ['images']))->response()->getData());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        ResponseHelper::sendSuccess('Data Fetch Successfully', ProductResource::make($this->productService->createProduct($request->validated()))->response()->getData());
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
    public function update(ProductRequest $request, Product $product)
    {
        ResponseHelper::sendSuccess('Data Fetch Successfully', ProductResource::make($this->productService->updateProduct($product->id, $request->validated()))->response()->getData());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product->id);
        ResponseHelper::sendSuccess('Data Deleted Successfully');
    }
}
