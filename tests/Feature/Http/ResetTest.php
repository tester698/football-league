<?php

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResetTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_reset(): void
    {
        $response = $this->post('/api/reset');

        $response->assertStatus(200);
        $response->assertJson(['status' => "OK"]);
    }
}
