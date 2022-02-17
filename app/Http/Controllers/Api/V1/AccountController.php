<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\AccountDepositAndWithdrawRequest;
use App\Http\Resources\AccountCollection;
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

    public function deposit(AccountDepositAndWithdrawRequest $request)
    {
        $account = Auth::user()->account();

        $accountTransactionService = new AccountTransactionService($account->id);

        $accountTransaction = $accountTransactionService->deposit($request->amount);
        
        return new AccountTransactionResource($accountTransaction);
    }

    public function withdraw(AccountDepositAndWithdrawRequest $request)
    {
        $account = Auth::user()->account();

        $accountTransactionService = new AccountTransactionService($account->id);

        $accountTransaction = $accountTransactionService->withdraw($request->amount);
        
        if (!$accountTransaction) {
            return response()->json([
                "balance" => Auth::user()->account()->balance,
                "message"=>"Balance is not enough",
                "error" => "Balance can not be under 0"
            ]);
        }

        return new AccountTransactionResource($accountTransaction);
    }
}
