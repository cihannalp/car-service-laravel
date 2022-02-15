<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Service;

Class OrderService
{

	public function getToTalPriceOfServices($service_ids)
	{
		return Service::whereIn('id', $service_ids)->get()->sum('price');
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
}