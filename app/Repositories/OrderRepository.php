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
use Illuminate\Support\Facades\Log;

class OrderRepository
{
    public function all(array $conditions = [], array $fields = [])
    {
        $fields = !empty($fields) ? $fields : "*";

        $orders = Order::select($fields);
        if (isset($conditions['status'])) {
            $orders->where('status', 'like', '%' . trim($conditions['status']) . '%');
        }

        if (isset($conditions['shipping_method'])) {
            $orders->where('shipping_method', 'like', '%' . trim($conditions['shipping_method']) . '%');
        }
        if (isset($conditions['shipping_description'])) {
            $orders->where('shipping_description', 'like', '%' . trim($conditions['shipping_description']) . '%');
        }
        if (isset($conditions['is_gift'])) {
            $orders->where('is_gift', $conditions['is_gift']);
        }
        if (isset($conditions['total_item_count_min'])) {
            $orders->where('total_item_count', '>=', $conditions['total_item_count_min']);
        }
        if (isset($conditions['total_item_count_max'])) {
            $orders->where('total_item_count', '<=', $conditions['total_item_count_max']);
        }
        if (isset($conditions['total_qty_ordered_min'])) {
            $orders->where('total_qty_ordered', '>=', $conditions['total_qty_ordered_min']);
        }
        if (isset($conditions['total_qty_ordered_max'])) {
            $orders->where('total_qty_ordered', '<=', $conditions['total_qty_ordered_max']);
        }
        if (isset($conditions['grand_total_min'])) {
            $orders->where('grand_total', '>=', $conditions['grand_total_min']);
        }
        if (isset($conditions['grand_total_max'])) {
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
        $order = Order::create($data);
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
