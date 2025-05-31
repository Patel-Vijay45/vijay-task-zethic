<?php

namespace App\Repositories;

use App\Events\OrderPlaced;
use App\Http\Controllers\Api\V1\PaymentWebhookController;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    public function all(array $conditions = [], array $fields = [])
    {
        $fields = !empty($fields) ? $fields : "*";

        $orders = Order::select($fields);
        if (!empty($conditions['status'])) {
            $orders->where('status', 'like', '%' . trim($conditions['status']) . '%');
        }

        if (!empty($conditions['shipping_method'])) {
            $orders->where('shipping_method', 'like', '%' . trim($conditions['shipping_method']) . '%');
        }
        if (!empty($conditions['shipping_description'])) {
            $orders->where('shipping_description', 'like', '%' . trim($conditions['shipping_description']) . '%');
        }
        if (!empty($conditions['is_gift'])) {
            $orders->where('is_gift', $conditions['is_gift']);
        }
        if (!empty($conditions['total_item_count_min'])) {
            $orders->where('total_item_count', '>=', $conditions['total_item_count_min']);
        }
        if (!empty($conditions['total_item_count_max'])) {
            $orders->where('total_item_count', '<=', $conditions['total_item_count_max']);
        }
        if (!empty($conditions['total_qty_ordered_min'])) {
            $orders->where('total_qty_ordered', '>=', $conditions['total_qty_ordered_min']);
        }
        if (!empty($conditions['total_qty_ordered_max'])) {
            $orders->where('total_qty_ordered', '<=', $conditions['total_qty_ordered_max']);
        }
        if (!empty($conditions['grand_total_min'])) {
            $orders->where('grand_total', '>=', $conditions['grand_total_min']);
        }
        if (!empty($conditions['grand_total_max'])) {
            $orders->where('grand_total', '<=', $conditions['grand_total_max']);
        }

        if (isset($conditions['parent_id'])) {
            $orders->where('parent_id', $conditions['parent_id']);
        }

        if (!empty($conditions['user_id'])) {
            $orders->whereIn('user_id', $conditions['user_id']);
        }

        return $orders
            ->orderBy('id', 'desc')
            ->paginate($conditions['per_page'] ?? config('constants.per_page'));
    }

    public function find($id)
    {
        return Order::findOrFail($id);
    }

    public function create(array $data)
    {
        $user = Auth::user();
        $order = DB::transaction(function () use ($user, $data) {
            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $data['address_id'],
                'status' => 'pending',
                'is_gift' => $data['is_gift'] ?? false,
                'total_item_count' => count($data['products']),
                'total_qty_ordered' => array_sum(array_column($data['products'], 'qnt')),
                'grand_total' => 0,
            ]);

            $grandTotal = 0;

            foreach ($data['products'] as $item) {
                $product = Product::findOrFail($item['id']);
                $price = $product->price;
                $qty = $item['qnt'];
                $product->stock -= $qty;

                $order->items()->create([
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $price,
                    'qnt' => $qty,
                    'product_id' => $product->id,
                    'subtotal' => $price * $qty,
                    'additional' => json_encode(['color' => 'red']), // optional
                ]);

                $grandTotal += $price * $qty;
                $product->save();
            }

            $order->update(['grand_total' => $grandTotal]);

            // Fire event

            return $order;
        });
        // ✅ Simulate payment
        app(PaymentWebhookController::class)->__invoke(new Request(['order_id' => $order->id]));;
        event(new OrderPlaced($order));
        return $order;
    }

    public function update($id, array $data)
    {
        $model = $this->find($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        $model = $this->find($id);
        return $model->delete();
    }
}
