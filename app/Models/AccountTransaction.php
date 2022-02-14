<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_account_id',
        'transaction_type_name',
        'transaction_amount'
    ];

    public function userAccount()
    {
        return $this->belongsTo(UserAccount::class);
    }
}
