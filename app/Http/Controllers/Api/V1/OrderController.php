<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    use AuthorizesRequests;
    public function __construct(private OrderService $orderService) {}


    /** 
     * List Orders.
     * 
     */
    public function index(Request $request)
    {
        ResponseHelper::sendSuccess('Data Fetch Successfully', OrderResource::collection($this->orderService->getAllOrders($request->all()))->response()->getData());
    }

    /** 
     * Make Order.
     * 
     */
    public function store(OrderRequest $request)
    {
        if ($order = $this->orderService->createOrder($request->validated())) {
            return  ResponseHelper::sendSuccess('Order Successfully', OrderResource::make($order));
        }
        return    ResponseHelper::sendSuccess('Payment failed. Please try again or use a different payment method.', code: Response::HTTP_PAYMENT_REQUIRED);
    }

    /** 
     * Update Order.
     * 
     */
    public function show(Order $order)
    {
        $user = Auth::user();
        if ($user->cannot('view', $order)) {
            return ResponseHelper::sendSuccess('You are not authorized to view this order', code: Response::HTTP_FORBIDDEN);
        }
        ResponseHelper::sendSuccess('Data Fetch Successfully', OrderResource::make($order));
    }
}
