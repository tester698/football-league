<?php

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PredictionTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_prediction(): void
    {
        $response = $this->get('/api/predict');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'name',
                'prediction'
            ]
        ]);
    }
}
