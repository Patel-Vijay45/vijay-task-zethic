<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'price',
        'parent_id',
        'stock',
        'additional',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    public function parent()
    {
        return $this->belongsTo(Product::class, 'parent_id');
    }

    public function variants()
    {
        return $this->hasMany(Product::class, 'parent_id');
    }


    protected static function booted()
    {
        static::deleting(function ($product) {
            if (! $product->isForceDeleting()) {
                // Soft delete variants (children)
                $product->variants()->each(function ($variant) {
                    $variant->delete();
                });

                // Soft delete images
                $product->images()->each(function ($image) {
                    $image->delete();
                });
            }
        });
    }
}
