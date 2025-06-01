<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $order = Order::findOrFail($request->input('order_id'));

        // Randomize or simulate payment outcome
        $status = rand(0, 1);
        if ($status) {
            $order->update(['status' => 'paid']);
        }
        return $status;
    }
}
