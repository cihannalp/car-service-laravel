<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orderService = new OrderService($request);

        $orders = $orderService->getOrders();
        
        return new OrderCollection($orders);
    }

    public function store(OrderRequest $request)
    {
        $orderService = new OrderService($request);
        
        $order = $orderService->createOrder();

        
        if (!$order) {
            return response()->json([
                "message"=>"Order could not be created",
                "error" => "Account balance is not enough."
            ]);
        }
        
        return new OrderResource($order);
    }
}
