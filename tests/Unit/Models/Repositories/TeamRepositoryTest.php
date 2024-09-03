<?php

namespace Tests\Unit\Models\Repositories;

use App\Models\Repositories\TeamRepository;
use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class TeamRepositoryTest extends TestCase
{
    private TeamRepository $service;

    private Team $team;

    protected function setUp(): void
    {
        $this->team = \Mockery::mock(Team::class);
        $this->service = new TeamRepository($this->team);
        parent::setUp();
    }

    /**
     * A basic unit test example.
     */
    public function test_get_teams_with_results(): void
    {
        $teams = $this->createMock(Collection::class);
        $this->team->shouldReceive('join')
                   ->with('results', 'teams.id', '=', 'results.team_id')
                   ->andReturnSelf();
        $this->team->shouldReceive('get')
                   ->with(
                       [
                           'teams.*',
                           'results.points as points',
                           'results.wins as matchesWon',
                           'results.draws as matchesDrawn',
                           'results.losses as matchesLost',
                           'results.played as matchesPlayed',
                           'results.goals_for as goalsFor',
                           'results.goals_against as goalsAgainst',
                           'results.goal_difference as goalDifference',
                       ]
                   )
                   ->andReturn($teams);
        $this->assertEquals($teams, $this->service->getAllWithResults());
    }
}
