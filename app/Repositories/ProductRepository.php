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
        if (isset($conditions['product_id']) && !empty($conditions['product_id'])) {
            $products->whereIn('id', $conditions['product_id']);
        }
        if (isset($conditions['name'])) {
            $products->where('name', 'like', '%'  . trim($conditions['name']) . '%');
        }

        if (isset($conditions['sku'])) {
            $products->where('sku', 'like', '%' . trim($conditions['sku']) . '%');
        }
        // ✅ Price range filter
        if (isset($conditions['price_min'])) {
            $products->where('price', '>=', $conditions['price_min']);
        }

        if (isset($conditions['price_max'])) {
            $products->where('price', '<=', $conditions['price_max']);
        }

        if (isset($conditions['parent_id'])) {
            $products->where('parent_id', $conditions['parent_id']);
        }

        if (isset($conditions['stock_min'])) {
            $products->where('stock', '>=', $conditions['stock_min']);
        }

        if (isset($conditions['stock_max'])) {
            $products->where('stock', '<=', $conditions['stock_max']);
        }
        if (isset($conditions['additional'])) {
            $products->where('additional', 'like', '%' . trim($conditions['additional']) . '%');
        }
        if (isset($conditions['category_id']) && !empty($conditions['category_id'])) {
            $products->whereHas('categories', function ($query) use ($conditions) {
                $query->whereIn('id', $conditions['category_id']);
            });
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
        return $product;
    }

    public function update($id, array $data)
    {
        $product = $this->find($id);
        $product->update($data);
        if (isset($data['category_id'])) {
            $product->categories()->sync($data['category_id']);
        }
        return $product;
    }

    public function delete($id)
    {
        $model = $this->find($id);
        return $model->delete();
    }
}
