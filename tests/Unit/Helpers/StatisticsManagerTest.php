<?php

namespace Tests\Unit\Helpers;

use App\Helpers\StatisticsManager;
use App\Models\Game;
use App\Models\Result;
use App\Models\Team;
use Database\Factories\ResultFactory;
use Tests\TestCase;

class StatisticsManagerTest extends TestCase
{
    private StatisticsManager $helper;

    private Team $team;

    private Game $game;

    private Result $result;

    protected function setUp(): void
    {
        $this->team = \Mockery::mock(Team::class);
        $this->game = \Mockery::mock(Game::class);
        $this->result = \Mockery::mock(Result::class);
        $this->helper = new StatisticsManager($this->team, $this->result, $this->game);
        parent::setUp();
    }

    /**
     * A basic unit test example.
     */
    public function test_clear_stats(): void
    {
        $teams = [];
        for ($i = 1; $i <= 3; $i++) {
            $result = \Mockery::mock(Result::class)->shouldReceive('getAttribute')->with('id')->once()->andReturn($i)
                              ->getMock();
            $team = \Mockery::mock(Team::class);
            $team->shouldReceive('getAttribute')->with('results')->once()->andReturn($result);
            $team->shouldReceive('getAttribute')->with('id')->once()->andReturn($i);
            $teams[] = $team;
        }
        $teams = collect($teams);
        $cleanedResults = [
            [
                'team_id' => 1,
                'id' => 1,
                'points' => 0,
                'wins' => 0,
                'draws' => 0,
                'losses' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'goal_difference' => 0,
                'played' => 0,
            ],
            [
                'team_id' => 2,
                'id' => 2,
                'points' => 0,
                'wins' => 0,
                'draws' => 0,
                'losses' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'goal_difference' => 0,
                'played' => 0,
            ],
            [
                'team_id' => 3,
                'id' => 3,
                'points' => 0,
                'wins' => 0,
                'draws' => 0,
                'losses' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'goal_difference' => 0,
                'played' => 0,
            ],
        ];
        $this->team->shouldReceive('all')->once()->andReturn($teams);
        $this->result->shouldReceive('upsert')->once()->with(
            $cleanedResults,
            ['id', 'team_id'],
            ['points', 'wins', 'draws', 'losses', 'goals_for', 'goals_against', 'goal_difference', 'played']
        );
        $this->helper->clearStats();
    }

    public function test_update_stats(): void
    {
        $this->result->shouldReceive('truncate')->once();
        for ($i = 1; $i < 3; $i++) {
            $game = \Mockery::mock(Game::class);
            $game->shouldReceive('getAttribute')->with('home_team_id')->andReturn($i);
            $game->shouldReceive('getAttribute')->with('home_team_goals')->andReturn($i);
            $game->shouldReceive('getAttribute')->with('away_team_goals')->andReturn(3 - $i);
            $game->shouldReceive('getAttribute')->with('away_team_id')->andReturn(3 - $i);
            $games[] = $game;
        }

        $this->game->shouldReceive('where')->with('is_played', true)->once()->andReturnSelf();
        $this->game->shouldReceive('get')->once()->andReturn($games);
        $factory = \Mockery::mock(ResultFactory::class);
        $factory->shouldReceive('createMany')->once();
        $this->result->shouldReceive('factory')->once()->andReturn($factory);

        $this->helper->updateStats();
    }

    public function test_update_team_stats_win(): void
    {
        $teamStats = [
            'team_id' => 1,
            'points' => 0,
            'wins' => 0,
            'draws' => 0,
            'losses' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'goal_difference' => 0,
            'played' => 0,
        ];

        $goalsFor = 2;
        $goalsAgainst = 1;

        $result = [
            'team_id' => 1,
            'points' => 3,
            'wins' => 1,
            'draws' => 0,
            'losses' => 0,
            'goals_for' => $goalsFor,
            'goals_against' => $goalsAgainst,
            'goal_difference' => $goalsFor - $goalsAgainst,
            'played' => 1,
        ];
        $this->helper->updateTeamStats($teamStats, $goalsFor, $goalsAgainst);
        $this->assertEquals($result, $teamStats);
    }

    public function test_update_team_stats_loss(): void
    {
        $teamStats = [
            'team_id' => 1,
            'points' => 0,
            'wins' => 0,
            'draws' => 0,
            'losses' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'goal_difference' => 0,
            'played' => 0,
        ];

        $goalsFor = 1;
        $goalsAgainst = 2;

        $result = [
            'team_id' => 1,
            'points' => 0,
            'wins' => 0,
            'draws' => 0,
            'losses' => 1,
            'goals_for' => $goalsFor,
            'goals_against' => $goalsAgainst,
            'goal_difference' => $goalsFor - $goalsAgainst,
            'played' => 1,
        ];
        $this->helper->updateTeamStats($teamStats, $goalsFor, $goalsAgainst);
        $this->assertEquals($result, $teamStats);
    }

    public function test_update_team_stats_draw(): void
    {
        $teamStats = [
            'team_id' => 1,
            'points' => 0,
            'wins' => 0,
            'draws' => 0,
            'losses' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'goal_difference' => 0,
            'played' => 0,
        ];

        $goalsFor = 1;
        $goalsAgainst = 1;

        $result = [
            'team_id' => 1,
            'points' => 1,
            'wins' => 0,
            'draws' => 1,
            'losses' => 0,
            'goals_for' => $goalsFor,
            'goals_against' => $goalsAgainst,
            'goal_difference' => $goalsFor - $goalsAgainst,
            'played' => 1,
        ];
        $this->helper->updateTeamStats($teamStats, $goalsFor, $goalsAgainst);
        $this->assertEquals($result, $teamStats);
    }
}
