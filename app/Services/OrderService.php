<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

Class OrderService
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function getOrders()
	{
		if($this->request->has('filters'))
		{
			return $this->filter();
		}

		$orders = Cache::remember('orders', 3600*24*30, function () {
		    return Auth::user()->account()->orders()->orderBy('id','desc')->paginate(10);
		});

		return $orders;

	}

	public function createOrder()
	{
		$accountId = Auth::user()->account()->id;

		$total = $this->getToTalPriceOfServices($this->request->service_ids);

        $order = new Order();

        $order->user_account_id = $accountId;
        $order->car_model_id = $this->request->car_model_id;
        $order->total = $total;
        
        $order->save();

        $this->createOrderDetails($order->id, $this->request->service_ids);

        $accountTransactionService = new AccountTransactionService($accountId);

        $accountTransaction = $accountTransactionService->pay($total);

        if(!$accountTransaction)
        {
        	return null;
        }

        Cache::forget('orders');

        return $order;
	}

	public function createOrderDetails($order_id, $service_ids)
	{
		foreach($service_ids as $service_id)
		{
			OrderDetail::create([
				'order_id' => $order_id,
				'service_id' => $service_id,
				'price' => Service::find($service_id)->price
			]);
		}
	}

	public function createResponseCollection($data)
	{
		return collect($data);
	}

	protected function filter()
    {
    	$orders = new Order();

    	foreach($this->request->filters as $filter => $value)
    	{
    		if($filter === 'fromDate')
    		{
    			$orders = $orders->where('created_at', '>', $value);
    		}

    		if($filter === 'toDate')
    		{
    			$orders = $orders->where('created_at', '<', $value);
    		}

    		if($filter === 'serviceName')
    		{
    			$orders = $orders->whereHas('orderDetails', function(Builder $query) use ($value){
    				$query->whereHas('service', function(Builder $query) use ($value) {
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