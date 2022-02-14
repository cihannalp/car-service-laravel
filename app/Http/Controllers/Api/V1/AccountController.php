<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\AccountAddBalanceRequest;
use App\Http\Resources\AccountCollection;
use App\Http\Resources\AccountResource;
use App\Models\UserAccount;
use App\Services\AccountTransactionService;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AccountTransactionResource;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $accounts = $user->accounts;
        
        return new AccountCollection($accounts);
    }

    public function addBalance(AccountAddBalanceRequest $request)
    {
        $account = Auth::user()->account();

        $accountTransactionService = new AccountTransactionService($account->id);

        $accountTransaction = $accountTransactionService->deposit($request->amount);
        
        return new AccountTransactionResource($accountTransaction);
    }
}
