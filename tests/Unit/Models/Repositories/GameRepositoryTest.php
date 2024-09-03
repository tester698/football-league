<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Repositories;

use App\Models\Game;
use App\Models\Repositories\GameRepository;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class GameRepositoryTest extends TestCase
{
    private Game $game;

    private GameRepository $repository;

    protected function setUp(): void
    {
        $this->game = \Mockery::mock(Game::class);
        $this->repository = new GameRepository($this->game);
        parent::setUp();
    }

    public function test_get_games_by_week(): void
    {
        $games = $this->createMock(Collection::class);
        $this->game->shouldReceive('where')
                   ->with('week', 1)
                   ->andReturnSelf();
        $this->game->shouldReceive('join')
                   ->twice()
                   ->andReturnSelf();
        $this->game->shouldReceive('get')->once()->andReturn($games);
        $this->assertEquals($games, $this->repository->getGamesByWeek(1));
    }

}
