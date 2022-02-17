<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orderService = new OrderService();

        $orders = $orderService->getOrders($request);
        
        return new OrderCollection($orders);
    }

    public function store(OrderRequest $request)
    {
        $orderService = new OrderService();
        
        $order = $orderService->createOrder($request);

        
        if (!$order) {
            return response()->json([
                "message"=>"Order could not be created",
                "error" => "Balance can not be under 0"
            ]);
        }
        
        return new OrderResource($order);
    }

    public function show($orderId)
    {
        $orderService = new OrderService();

        $order = $orderService->getOrder($orderId);

        return new OrderResource($order);
    }

    public function cancel($orderId)
    {
        $orderService = new OrderService();

        $order = $orderService->cancelOrder($orderId);

        return new OrderResource($order);
    }
}
