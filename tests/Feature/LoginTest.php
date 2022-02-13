<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['password'=>'password']);

    }

    public function test_a_user_can_login()
    {

        $response = $this->json('POST','/api/v1/auth/login', 
            [
                "email" => $this->user->email, 
                "password" => 'password'
            ]);

        $response->assertStatus(200);

        $this->assertAuthenticatedAs($this->user);
    }
    
    /**
     * @dataProvider invalidValidation
     */
    public function test_login_fails_with_validation_errors($data)
    {
        $response = $this->json('POST','/api/v1/auth/login', $data);

        $response->assertStatus(422);
    }

    /**
     * @dataProvider invalidCredentials
     */
    public function test_login_fails_if_credential_doesnt_match($data)
    {
        $response = $this->json('POST','/api/v1/auth/login', $data);

        $response->assertStatus(401);
    }

    public function invalidCredentials()
    {
        return [
            [
                [
                    'email' => 'email@email.com',
                    'password' => 'password1t',
                ]
            ],
            [
                [
                    'email' => 'email@email.com',
                    'password' => 'password',
                ]
            ]
        ];
    }

    public function invalidValidation()
    {
        return [
            [
                [
                    'email' => 'testemailtest.com',
                    'password' => 'passwordtest',
                ]
            ],
            [
                [
                    'email' => 'testemail@test.com'
                ]
            ],
            [
                [
                    'password' => 'passwordtest',
                ]
            ]
        ];
    }
}
