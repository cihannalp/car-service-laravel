<?php

namespace App\Services;

use App\Models\AccountTransaction;

class AccountTransactionService
{
	protected $accountId;

	public function __construct($accountId)
	{
		$this->accountId = $accountId;
	}

	public function deposit($amount)
	{
		$accountActivity = AccountTransaction::create([
			'user_account_id' => $this->accountId,
			'transaction_type_name' => 'deposit',
			'transaction_amount' => $amount
		]);
		
		return $accountActivity->fresh();
	}

	public function refund($amount)
	{
		$accountActivity = AccountTransaction::create([
			'user_account_id' => $this->accountId,
			'transaction_type_name' => 'refund',
			'transaction_amount' => $amount
		]);

		return $accountActivity->fresh();
	}

	public function withdraw($amount)
	{

		$accountActivity = AccountTransaction::create([
			'user_account_id' => $this->accountId,
			'transaction_type_name' => 'withdraw',
			'transaction_amount' => -1*($amount)
		]);

		return $accountActivity->fresh();
	}

	public function pay($amount)
	{
		$accountActivity = AccountTransaction::create([
			'user_account_id' => $this->accountId,
			'transaction_type_name' => 'pay',
			'transaction_amount' => -1*($amount)
		]);

		return $accountActivity->fresh();
	}
}