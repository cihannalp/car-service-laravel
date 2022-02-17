<?php

namespace Tests\Feature;

use App\Models\CarModel;
use App\Models\Service;
use App\Models\User;
use App\Services\AccountTransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $requestData;
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['password'=>'password']);

        CarModel::factory()->create();

        Service::insert([
            [
                'service' => 'Cleaning',
                'price' => 10
            ],
            [
                'service' => 'Maintenance',
                'price' => 20
            ]
        ]);

        $this->requestData = [
            "car_model_id" => 1,
            "service_ids" => [
                1, 2
            ]
        ];

        $this->userAccount = $this->user->account();
    }
    
    public function test_a_user_can_create_a_new_order_and_order_details()
    {
        $accountTransactionService = new AccountTransactionService($this->user->account()->id);
        $accountTransactionService->deposit(50);

        $response = $this->actingAs($this->user)->json('POST', '/api/v1/orders', $this->requestData);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_details',2);

        $response->assertStatus(201);
    }

    public function test_balance_is_adjusted_with_an_order_and_order_cancel()
    {
        $accountTransactionService = new AccountTransactionService($this->user->account()->id);
        $accountTransactionService->deposit(50);

        $balance = $this->user->account()->balance;

        $response = $this->actingAs($this->user)->json('POST', '/api/v1/orders', $this->requestData);

        $orderSum = Service::find(1)->price + Service::find(2)->price;

        $this->assertEquals($balance-$orderSum, $this->user->account()->balance);
    }

    public function test_order_cannot_be_created_if_balance_is_not_enough()
    {
        $response = $this->actingAs($this->user)->json('POST', '/api/v1/orders', $this->requestData);

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_details',0);
    }
}
