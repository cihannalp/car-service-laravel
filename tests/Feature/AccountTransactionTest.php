<?php

namespace Tests\Feature;

use App\Models\AccountTransaction;
use App\Models\User;
use App\Services\AccountTransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountTransactionTest extends TestCase
{
    use RefreshDatabase;
    
    protected $user;

    protected $userAccount;

    protected $accountTransactionService;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['password'=>'password']);

        $this->userAccount = $this->user->accounts()->first();

        $this->accountTransactionService = new AccountTransactionService($this->userAccount->id);

        $this->accountTransactionService->deposit(10);

        $this->accountTransactionService->deposit(20);

        $this->accountTransactionService->deposit(30);
    }

    public function test_last_transaction_correctly_retrieved_if_trashed_or_not()
    {
        $this->assertFalse($this->accountTransactionService->checkIfLastTransactionTrashed());

        AccountTransaction::latest()->first()->delete();

        $this->assertTrue($this->accountTransactionService->checkIfLastTransactionTrashed());
    }

    public function test_transaction_services_working_and_balance_changed_properly()
    {
        $this->accountTransactionService->pay(10);
        $this->assertEquals(50 ,$this->user->accounts()->first()->balance );

        $this->accountTransactionService->refund(10);
        $this->assertEquals(60 ,$this->user->accounts()->first()->balance );

        $this->accountTransactionService->withdraw(33);
        $this->assertEquals(27 ,$this->user->accounts()->first()->balance );

        $this->accountTransactionService->undoLastTransaction();
        $this->assertEquals(60 ,$this->user->accounts()->first()->balance );

        $this->accountTransactionService->deposit(10);
        $this->assertEquals(70 ,$this->user->accounts()->first()->balance );
    }

    public function test_last_trasaction_undoed_and_account_balance_properly_changed()
    { 
        $this->accountTransactionService->undoLastTransaction();

        $this->assertEquals(30 ,$this->user->accounts()->first()->balance );

        $this->accountTransactionService->undoLastTransaction();

        $this->assertEquals(10 ,$this->user->accounts()->first()->balance );
    }

    public function test_transaction_deleted_by_id_and_balance_properly_changed()
    { 
        $this->accountTransactionService->deleteTransaction(2);

        $this->assertEquals(40 ,$this->user->accounts()->first()->balance );

        $this->accountTransactionService->deleteTransaction(1);

        $this->assertEquals(30 ,$this->user->accounts()->first()->balance );
    }
}
