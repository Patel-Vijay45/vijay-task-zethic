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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        ResponseHelper::sendSuccess('Data Fetch Successfully', OrderResource::collection($this->orderService->getAllOrders($request->all()))->response()->getData());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        ResponseHelper::sendSuccess('Order Successfully', OrderResource::make($this->orderService->createOrder($request->validated())));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $user = Auth::user();
        // $this->authorize('view', $order);
        if ($user->cannot('view', $order)) {
            return ResponseHelper::sendSuccess('You are not authorized to view this order', code: Response::HTTP_FORBIDDEN);
        }
        ResponseHelper::sendSuccess('Data Fetch Successfully', OrderResource::make($order));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
