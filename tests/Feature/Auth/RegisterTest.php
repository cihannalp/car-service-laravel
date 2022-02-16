<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register()
    {

        $user = [
            'name' => 'cihan',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ];

        $response = $this->post('/api/v1/auth/register', $user);

        $response
            ->assertStatus(201)
            ->assertJson([
                'token' => true,
            ]);

        array_splice($user, 2, 2);

        $this->assertDatabaseHas('users', $user);
    }

    public function test_register_fails_if_email_already_exists()
    {
        $user = [
            'name' => 'cihan',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ];

        $response = $this->post('/api/v1/auth/register', $user);

        $response = $this->json('POST', '/api/v1/auth/register', $user);

        $response
            ->assertStatus(422)
            ->assertJsonPath('errors.email.0', "The email has already been taken.");
        $this->assertDatabaseCount('users', 1);
    }

    /**
     * @dataProvider invalidCredentials
     */
    public function test_register_fails_with_error_message_when_validation_fails($data)
    {
        $response = $this->json('POST', '/api/v1/auth/register', $data);
        
        $response->assertStatus(422);

        $this->assertDatabaseCount('users', 0);
    }

    public function invalidCredentials()
    {
        return [
            [
                [
                    'name' => 'cihan',
                    'email' => 'testemail@test.com',
                    'password' => 'passwordtest',
                ]
            ],
            [
                [
                    'name' => 'cihan',
                    'email' => 'testemail@test.com',
                    'password_confirmation' => 'passwordtest',
                ]
            ],
            [
                [
                    'name' => 'cihan',
                    'email' => 'testemailtest.com',
                    'password' => 'passwordtest',
                    'password_confirmation' => 'passwordtest',
                ]
            ]
        ];
    }
}
