<?php

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CurrentWeekTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_weeks(): void
    {
        $response = $this->get('/api/week');

        $response->assertStatus(200);
        $response->assertJsonStructure(['current_week', 'max_week']);
    }
}
