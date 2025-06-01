<?php

namespace App\Services;

use App\Events\OrderPlaced;
use App\Http\Controllers\Api\V1\PaymentWebhookController;
use App\Http\Requests\PaymentWebhookRequest;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(private OrderRepository $orderRepo, private ProductRepository $productRepo) {}

    public function getAllOrders(array $conditions = [], array $fields = [])
    {
        $user = Auth::user();
        if ($user->role == 'user') {
            $conditions['user_id'] = [$user->id];
        }
        return $this->orderRepo->all($conditions);
    }

    public function getOrder($id)
    {
        return $this->orderRepo->find($id);
    }

    public function createOrder(array $data)
    {

        // return $this->orderRepo->create($data);

        $order = DB::transaction(function () use ($data) {
            $user = Auth::user();

            $order = $this->orderRepo->create([
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
                $product = $this->productRepo->find($item['id']);
                $qty = $item['qnt'];

                $product->decrement('stock', $qty);

                $order->items()->create([
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'qnt' => $qty,
                    'product_id' => $product->id,
                    'subtotal' => $product->price * $qty,
                    'additional' => json_encode(['color' => 'red']),
                ]);

                $grandTotal += $product->price * $qty;
                $product->save();
            }

            $this->orderRepo->update($order->id, ['grand_total' => $grandTotal]);
            return $order;
        });
        if (app(PaymentWebhookController::class)->__invoke(new PaymentWebhookRequest(['order_id' => $order->id]))) {
            event(new OrderPlaced($order));
            return $order;
        }
        return false;
    }

    public function updateOrder($id, array $data)
    {
        return $this->orderRepo->update($id, $data);
    }

    public function deleteOrder($id)
    {
        return $this->orderRepo->delete($id);
    }
}
