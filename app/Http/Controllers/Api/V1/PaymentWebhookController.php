<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentWebhookRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentWebhookController extends Controller
{
    /** 
     * Replicate Payment Gatway.
     * 
     */
    public function __invoke(PaymentWebhookRequest $request)
    {
        $order = Order::findOrFail($request->input('order_id'));

        $status = rand(0, 1);
        if ($status) {
            $order->update(['status' => 'paid']);
        }
        return $status;
    }
}
