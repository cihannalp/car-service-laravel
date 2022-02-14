<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'balance',
        'is_default'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accountTransactions()
    {
        return $this->hasMany(AccountTransaction::class);
    }

    public function lastAccountTransaction()
    {
        return $this->accountTransactions()->orderBy('desc','id')->first();
    }
}
