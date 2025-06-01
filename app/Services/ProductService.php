<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\ProductImage;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{

    public function __construct(private ProductRepository $productRepo) {}

    public function getAllProducts(array $conditions = [], array $fields = [], array $with = [])
    {
        return $this->productRepo->all($conditions, $fields, $with);
    }

    public function getProduct($id)
    {
        return $this->productRepo->find($id);
    }

    public function createProduct(array $data)
    {
        DB::beginTransaction();

        try {
            $product = $this->productRepo->create($data);
            $productImages = [];

            foreach ($data['images'] as $key => $val) {
                $imagePath = FileUploadHelper::uploadFile($val, 'products');
                $productImages[] = [
                    'product_id' => $product->id,
                    'position' => $key + 1,
                    'path' => Storage::url('public/' . $imagePath),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            ProductImage::insert($productImages);
            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateProduct($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $product= $this->productRepo->update($id, $data);
            
            if (!empty($data['images'])) {
                // Delete old image files
                // foreach ($product->images as $image) {
                //     // Convert public URL to storage path
                //     $storagePath = str_replace('/storage/', 'public/', $image->path);
                //     Storage::delete($storagePath);
                // }

                // Delete old image DB records
                $product->images()->delete();

                // Add new image records
                $productImages = [];

                foreach ($data['images'] as $key => $val) {
                    $imagePath = FileUploadHelper::uploadFile($val, 'products'); // returns 'products/filename.jpg'
                    $productImages[] = [
                        'product_id' => $product->id,
                        'position' => $key + 1,
                        'path' => Storage::url('public/' . $imagePath),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                ProductImage::insert($productImages);
            }

            return $product;
        });
    }

    public function deleteProduct($id)
    {
        return $this->productRepo->delete($id);
    }
}
