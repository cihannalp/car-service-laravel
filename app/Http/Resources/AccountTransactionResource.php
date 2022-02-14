<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountTransactionResource extends JsonResource
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
            'id' => $this->id,
            'user_account_id' => $this->user_account_id,
            'transaction_type_name' => $this->transaction_type_name,
            'user_account' => new AccountResource($this->userAccount),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
