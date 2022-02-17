<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class OrderService
{
    protected $request;

    public function __construct()
    {
    }

    public function getOrders($request)
    {
        if ($request->has('filters')) {
            return $this->filter($request);
        }

        $orders = Cache::remember('orders', 3600*24*30, function () {
            return Auth::user()->account()->orders()->orderBy('id', 'desc')->paginate(10);
        });

        return $orders;
    }

    public function createOrder($request)
    {
        $accountId = Auth::user()->account()->id;

        $total = $this->getToTalPriceOfServices($request->service_ids);

        $order = new Order();

        $order->user_account_id = $accountId;
        $order->car_model_id = $request->car_model_id;
        $order->total = $total;

        $accountTransactionService = new AccountTransactionService($accountId);

        $accountTransaction = $accountTransactionService->pay($total);

        if (!$accountTransaction) {
            return null;
        }

        $order->save();

        $this->createOrderDetails($order->id, $request->service_ids);

        Cache::forget('orders');

        return $order;
    }

    public function getOrder($orderId)
    {
        $order = Cache::remember('order', 3600*24*30, function () use ($orderId){
            return Order::find($orderId);
        });

        return $order;
    }

    public function cancelOrder($orderId)
    {
        $order = Order::find($orderId);

        $accountId = Auth::user()->account()->id;

        $accountTransactionService = new AccountTransactionService($accountId);

        $accountTransactionService->refund($order->total);

        $order->is_canceled = 1;

        $order->save();

        return $order;
    }

    public function createOrderDetails($order_id, $service_ids)
    {
        foreach ($service_ids as $service_id) {
            OrderDetail::create([
                'order_id' => $order_id,
                'service_id' => $service_id,
                'price' => Service::find($service_id)->price
            ]);
        }
    }

    protected function filter($request)
    {
        $orders = new Order();

        foreach ($request->filters as $filter => $value) {

            if ($filter === 'canceled') {
                $orders = $orders->where('is_canceled', $value);
            }

            if ($filter === 'fromDate') {
                $orders = $orders->where('created_at', '>', $value);
            }

            if ($filter === 'toDate') {
                $orders = $orders->where('created_at', '<', $value);
            }

            if ($filter === 'serviceName') {
                $orders = $orders->whereHas('orderDetails', function (Builder $query) use ($value) {
                    $query->whereHas('service', function (Builder $query) use ($value) {
                        $query->where('service', $value);
                    });
                });
            }
        }

        return $orders->orderBy('id', 'desc')->paginate(10);
    }

    public function getToTalPriceOfServices($service_ids)
    {
        return Service::whereIn('id', $service_ids)->get()->sum('price');
    }
}
