<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'position',
        'image',
        'category_banner',
        'status',
        'additional',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }
    protected static function booted()
    {
        static::deleting(function ($category) {
            if (! $category->isForceDeleting()) { 
                $category->products()->each(function ($variant) {
                    $variant->delete();
                }); 
            }
        });
    }
}
