<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::factory()->count(5)->create();

        // Create 20 products
        $products = Product::factory()->count(20)->create();

        // Attach each product to 1–3 random categories
        foreach ($products as $product) {
            $product->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );

            ProductImage::factory()
                ->count(rand(1, 4))
                ->for($product) // Assign product_id
                ->create();
        }
    }
}
