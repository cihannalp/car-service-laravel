<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderRequest;
use App\Http\Resources\AccountTransactionResource;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderDetailCollection;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\AccountTransactionService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->account()->orders;
        
        return new OrderCollection($orders);
    }

    public function store(OrderRequest $request)
    {
        $accountId = Auth::user()->account()->id;
        $orderService = new OrderService();
        
        $total = $orderService->getToTalPriceOfServices($request->service_ids);

        $order = new Order();

        $order->user_account_id = $accountId;
        $order->car_model_id = $request->car_model_id;
        $order->total = $total;
        
        $order->save();

        $orderService->createOrderDetails($order->id, $request->service_ids);

        $accountTransactionService = new AccountTransactionService($accountId);

        $accountTransaction = $accountTransactionService->pay($total);

        if(!$accountTransaction)
        {
            return response()->json([
                "message"=>"Order could not be created",
                "error" => "Account balance is not enough." 
            ]);
        }

        return new OrderResource($order);
    }
}
