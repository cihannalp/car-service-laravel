<?php

namespace App\Observers;

use App\Models\AccountTransaction;

class AccountTransactionObserver
{
    /**
     * Handle the AccountTransaction "created" event.
     *
     * @param  \App\Models\AccountTransaction  $accountTransaction
     * @return void
     */
    public function created(AccountTransaction $accountTransaction)
    {

        $account = $accountTransaction->userAccount;
        $account->balance = $account->balance + $accountTransaction->transaction_amount;
        
        $account->save();
    }

    /**
     * Handle the AccountTransaction "updated" event.
     *
     * @param  \App\Models\AccountTransaction  $accountTransaction
     * @return void
     */
    public function updated(AccountTransaction $accountTransaction)
    {
        //
    }

    /**
     * Handle the AccountTransaction "deleted" event.
     *
     * @param  \App\Models\AccountTransaction  $accountTransaction
     * @return void
     */
    public function deleted(AccountTransaction $accountTransaction)
    {
        $account = $accountTransaction->account();

        $account->balance = $account->balance - $accountTransaction->amount;

        $account->save();
    }

    /**
     * Handle the AccountTransaction "restored" event.
     *
     * @param  \App\Models\AccountTransaction  $accountTransaction
     * @return void
     */
    public function restored(AccountTransaction $accountTransaction)
    {
        $account = $accountTransaction->account();

        $account->balance = $account->balance + $accountTransaction->amount;

        $account->save();
    }

    /**
     * Handle the AccountTransaction "force deleted" event.
     *
     * @param  \App\Models\AccountTransaction  $accountTransaction
     * @return void
     */
    public function forceDeleted(AccountTransaction $accountTransaction)
    {
        //
    }
}
