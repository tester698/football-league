<?php

namespace Tests\Unit\Helpers;

use App\Contracts\StatisticsManagerInterface;
use App\Helpers\GamePlaySimulator;
use App\Models\Game;
use Tests\TestCase;

class GamePlaySimulatorTest extends TestCase
{
    private GamePlaySimulator $helper;

    private Game $game;

    private StatisticsManagerInterface $statisticsManager;

    protected function setUp(): void
    {
        $this->game = \Mockery::mock(Game::class);
        $this->statisticsManager = \Mockery::mock(StatisticsManagerInterface::class);
        $this->helper = new GamePlaySimulator($this->game, $this->statisticsManager);
        parent::setUp();
    }

    public function test_play_week(): void
    {
        $games = [];
        for ($i = 1; $i <= 3; $i++) {
            $game = \Mockery::mock(Game::class);
            $game->shouldReceive('setAttribute')->with('is_played', true)->once();
            $game->shouldReceive('setAttribute')->with('home_team_goals', \Mockery::type('int'))->once();
            $game->shouldReceive('setAttribute')->with('away_team_goals', \Mockery::type('int'))->once();
            $game->shouldReceive('save')->once();
            $games[] = $game;
        }
        $games = collect($games);
        $this->game->shouldReceive('where')->with('week', 1)->once()->andReturnSelf();
        $this->game->shouldReceive('get')->once()->andReturn($games);
        $this->statisticsManager->shouldReceive('updateStats')->once();

        $this->helper->playWeek(1);
    }
}
