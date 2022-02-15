<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_a_user_can_create_a_new_order()
    {
        $response = $this->json('POST','/api/v1/orders');

        $response->assertStatus(201);

    }
}
