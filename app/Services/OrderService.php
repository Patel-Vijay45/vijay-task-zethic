<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function __construct(private OrderRepository $orderRepo) {}

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

        return $this->orderRepo->create($data);
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
