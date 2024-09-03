<?php

namespace Tests\Feature\Http;

use Tests\TestCase;

class ResultsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_teams_stat(): void
    {
        $response = $this->get('/api/results');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'city',
                'points',
                'matchesWon',
                'matchesDrawn',
                'matchesLost',
                'goalsFor',
                'goalsAgainst',
                'goalDifference'
            ]
        ]);
    }

    public function test_games_by_week(): void
    {
        $response = $this->get('/api/results/1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'home_team',
                'away_team',
                'home_team_goals',
                'away_team_goals',
                'week',
                'is_played',
            ]
        ]);
    }

    public function test_play_week(): void
    {
        $response = $this->post('/api/results', ['week_id' => 1]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'home_team_id',
                'away_team_id',
                'home_team_goals',
                'away_team_goals',
                'week',
                'is_played',
            ]
        ]);
    }
}
