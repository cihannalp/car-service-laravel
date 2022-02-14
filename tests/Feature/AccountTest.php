<?php

namespace Tests\Feature;

use App\Http\Resources\AccountCollection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;
    
    protected $user;

    protected $userAccount;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['password'=>'password']);

        $this->userAccount = $this->user->accounts()->first();

    }

    public function test_an_account_created_when_a_new_user_registered()
    {
        $user = [
            'name' => 'cihan',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ];

        $this->post('/api/v1/auth/register', $user);

        $user = User::where('email', 'testemail@test.com')->first();

        $this->assertDatabaseHas('user_accounts', [
            'user_id' => $user->id,
        ]); 
    }

    public function test_a_user_can_get_accounts_details()
    {
        $response = $this->actingAs($this->user)->json('GET','/api/v1/accounts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                            'id',
                            'user_id',
                            'balance'
                    ],
                ],
                'description'
            ]);

        $this->assertInstanceOf(Collection::class, $response->getOriginalContent());
    }

    public function test_user_can_add_balance_to_account()
    {
        $response = $this->actingAs($this->user)
            ->json('POST','/api/v1/accounts/'.$this->user->accounts->first()->id.'/addBalance',
                [
                    'amount' => 10,
                ]
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('user_accounts', [
            'balance' => 10
        ]);
    }
}
