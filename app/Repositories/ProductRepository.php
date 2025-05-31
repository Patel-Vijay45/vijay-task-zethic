<?php

namespace App\Repositories;

use App\Helpers\FileUploadHelper;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductRepository
{
    public function all(array $conditions = [], array $fields = [], array $with = [])
    {
        $fields = !empty($fields) ? $fields : "*";

        $products = Product::select($fields);

        if (isset($with) && !empty($with)) {
            $products->with($with);
        }
        if (!empty($conditions['name'])) {
            $products->where('name', 'like', '%' . trim($conditions['name']) . '%');
        }

        if (!empty($conditions['sku'])) {
            $products->where('sku', 'like', '%' . trim($conditions['sku']) . '%');
        }
        // ✅ Price range filter
        if (!empty($conditions['price_min'])) {
            $products->where('price', '>=', $conditions['price_min']);
        }

        if (!empty($conditions['price_max'])) {
            $products->where('price', '<=', $conditions['price_max']);
        }

        if (isset($conditions['parent_id'])) {
            $products->where('parent_id', $conditions['parent_id']);
        }

        // ✅ Stock range filter
        if (!empty($conditions['stock_min'])) {
            $products->where('stock', '>=', $conditions['stock_min']);
        }

        if (!empty($conditions['stock_max'])) {
            $products->where('stock', '<=', $conditions['stock_max']);
        }
        if (!empty($conditions['additional'])) {
            $products->where('additional', 'like', '%' . trim($conditions['additional']) . '%');
        }

        return $products
            ->orderBy('id', 'desc')
            ->paginate($conditions['per_page'] ?? config('constants.per_page'));
    }

    public function find($id)
    {
        return Product::findOrFail($id);
    }

    public function create(array $data)
    {

        $product = Product::create($data);
        $product->categories()->sync($data['category_id']);
        $productImage = [];
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
        ProductImage::insert($productImage);
        return $product;
    }

    public function update($id, array $data)
    {

        return DB::transaction(function () use ($id, $data) {
            $product = $this->find($id); // assuming you have this method in the repo

            // Update basic product fields
            $product->update($data);

            // Sync categories
            if (isset($data['category_id'])) {
                $product->categories()->sync($data['category_id']);
            }

            // If images are uploaded, delete old and insert new
            // 3. If new images are sent, remove old and add new
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

    public function delete($id)
    {
        $model = $this->find($id);
        return $model->delete();
    }
}
