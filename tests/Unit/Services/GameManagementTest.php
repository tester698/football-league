<?php

namespace Tests\Unit\Services;

use App\Contracts\GamePlaySimulatorInterface;
use App\Contracts\StatisticsManagerInterface;
use App\Contracts\TeamScheduleGeneratorInterface;
use App\Models\Game;
use App\Models\Team;
use App\Services\GameManagement;
use Database\Factories\GameFactory;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class GameManagementTest extends TestCase
{
    private GameManagement $service;

    private Game $game;

    private TeamScheduleGeneratorInterface $scheduler;

    private GamePlaySimulatorInterface $simulator;

    private StatisticsManagerInterface $statisticsManager;

    protected function setUp(): void
    {
        $this->game = \Mockery::mock(Game::class);
        $this->scheduler = \Mockery::mock(TeamScheduleGeneratorInterface::class);
        $this->simulator = \Mockery::mock(GamePlaySimulatorInterface::class);
        $this->statisticsManager = \Mockery::mock(StatisticsManagerInterface::class);
        $this->service = new GameManagement($this->scheduler, $this->simulator, $this->statisticsManager, $this->game);
        parent::setUp();
    }

    public function test_play_week(): void
    {
        $this->simulator->shouldReceive('playWeek')->with(1);
        $games = $this->createMock(Collection::class);
        $this->game->shouldReceive('where')
                   ->with('week', 1)
                   ->andReturnSelf();
        $this->game->shouldReceive('get')->once()->andReturn($games);
        $this->assertEquals($games, $this->service->playWeek(1));
    }

    public function test_reset_schedule(): void
    {
        $teamMocks = [];

        for ($i = 1; $i <= 8; $i++) {
            $teamMocks[$i] =
                \Mockery::mock(Team::class)->shouldReceive('getAttribute')->with('id')->andReturn($i)->getMock();
        }
        $weeks = [
            [
                ['home' => $teamMocks[1], 'away' => $teamMocks[2]],
                ['home' => $teamMocks[3], 'away' => $teamMocks[4]],
            ],
            [
                ['home' => $teamMocks[5], 'away' => $teamMocks[6]],
                ['home' => $teamMocks[7], 'away' => $teamMocks[8]],
            ],
        ];
        $factory = \Mockery::mock(GameFactory::class);
        $this->scheduler->shouldReceive('generateSchedule')->once()->andReturn($weeks);
        $this->statisticsManager->shouldReceive('updateStats')->once();
        $this->game->shouldReceive('truncate')->once();
        $this->game->shouldReceive('factory')->once()->andReturn($factory);
        $factory->shouldReceive('createMany')->once()->with([
            [
                'home_team_id' => 1,
                'away_team_id' => 2,
                'home_team_goals' => 0,
                'away_team_goals' => 0,
                'week' => 1,
                'is_played' => false
            ],
            [
                'home_team_id' => 3,
                'away_team_id' => 4,
                'home_team_goals' => 0,
                'away_team_goals' => 0,
                'week' => 1,
                'is_played' => false
            ],
            [
                'home_team_id' => 5,
                'away_team_id' => 6,
                'home_team_goals' => 0,
                'away_team_goals' => 0,
                'week' => 2,
                'is_played' => false
            ],
            [
                'home_team_id' => 7,
                'away_team_id' => 8,
                'home_team_goals' => 0,
                'away_team_goals' => 0,
                'week' => 2,
                'is_played' => false
            ],
        ]);

        $this->service->resetSchedule();
    }

    public function test_get_current_week(): void
    {
        $this->game->shouldReceive('where')
                   ->with('is_played', true)
                   ->andReturnSelf();
        $this->game->shouldReceive('max')
                   ->with('week')
                   ->andReturn(1);
        $this->assertEquals(1, $this->service->getCurrentWeek());
    }

    public function test_get_total_weeks(): void
    {
        $this->game->shouldReceive('max')
                   ->with('week')
                   ->andReturn(1);
        $this->assertEquals(1, $this->service->getTotalWeeks());
    }
}
