<?php

namespace App\Services;

use App\Models\AccountTransaction;
use App\Models\UserAccount;

class AccountTransactionService
{
	protected $accountId;
	protected $account;

	public function __construct($accountId)
	{
		$this->accountId = $accountId;
		$this->account = UserAccount::find($accountId);
	}

	public function deposit($amount)
	{
		return $this->createAndSaveTransaction($amount, 'deposit');
	}

	public function refund($amount)
	{
		return $this->createAndSaveTransaction($amount, 'refund');
	}

	public function withdraw($amount)
	{

		return $this->createAndSaveTransaction($amount, 'withdraw', -1);
	}

	public function pay($amount)
	{
		return $this->createAndSaveTransaction($amount, 'pay', -1);
	}

	public function undoLastTransaction()
	{
		$this->account->accountTransactions()->orderBy('id', 'desc')->first()->delete();
	}

	public function deleteTransaction($byId)
	{
		AccountTransaction::find($byId)->delete();
	}

	public function createAndSaveTransaction($amount, $transactionTypeName, $multiply=1)
	{
		$accountTransaction = new AccountTransaction();

		$accountTransaction->user_account_id = $this->accountId;
		$accountTransaction->transaction_type_name = $transactionTypeName;
		$accountTransaction->transaction_amount = $amount*$multiply;

		if($this->account->balance + $accountTransaction->transaction_amount < 0)
		{
			return null;
		}

		$accountTransaction->save();

		return $accountTransaction;
	}

	public function checkIfLastTransactionTrashed()
	{
		return $this->account->accountTransactions()->withTrashed()->latest()->first()->trashed();
	}
}