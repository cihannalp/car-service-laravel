<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'balance' => $this->userAccount->balance,
            'order_id' => $this->id,
            'user_id' => Auth::id(),
            'user_name' => $this->userAccount->user->name,
            'user_account_id' => $this->userAccount->id,
            'car_model_id' => $this->car_model_id,
            'car_model' => $this->CarModel->model,
            'total' => $this->total,
            'is_canceled' => $this->is_canceled,
            'orderDetails' => new OrderDetailCollection($this->orderDetails()->with('service')->get()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
