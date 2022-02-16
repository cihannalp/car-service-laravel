<?php

namespace Tests\Feature;

use App\Models\CarModel;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

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
        $this->userAccount = $this->user->accounts()->first();
    }
    
    public function test_a_user_can_create_a_new_order()
    {
        $data = [
            "car_model_id" => 1,
            "service_ids" => [
                1, 2
            ]
        ];
        $response = $this->actingAs($this->user)->json('POST', '/api/v1/orders', $data);

        $response->assertStatus(200);
    }
}
