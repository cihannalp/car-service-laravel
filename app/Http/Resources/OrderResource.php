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
            'order_id' => $this->id,
            'user_id' => Auth::id(),
            'user_account_id' => $this->userAccount->id,
            'balance' => $this->userAccount->balance,
            'orderDetails' => new OrderDetailCollection($this->orderDetails),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
