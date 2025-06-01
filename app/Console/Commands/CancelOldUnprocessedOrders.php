<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelOldUnprocessedOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel unprocessed orders older than 1 hour';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoff = now()->subHour();
        Log::info("Order cancell.");
        $orders = Order::with('items')->where('status', 'pending')
            ->where('created_at', '<=', $cutoff)
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No unprocessed orders found.');
            return;
        }

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $product = $item->product;

                if ($product) {
                    $product->increment('stock', $item->qnt);
                }
            }
            $order->update(['status' => 'cancelled']);
            Log::info("Order #{$order->id} cancelled due to inactivity.");
        }

        $this->info("Cancelled {$orders->count()} unprocessed order(s).");
    }
}
