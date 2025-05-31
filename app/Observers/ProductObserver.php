<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        DB::table('product_logs')->insert([
            'product_id' => $product->id,
            'user_id' => Auth::user()?->id ?? null,
            'action' => 'created',
            'logged_at' => now(),
        ]);
    }

    public function deleted(Product $product): void
    {
        DB::table('product_logs')->insert([
            'product_id' => $product->id,
            'user_id' => Auth::user()?->id ?? null,
            'action' => 'deleted',
            'logged_at' => now(),
        ]);
    }
}
