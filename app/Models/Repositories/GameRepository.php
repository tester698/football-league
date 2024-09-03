<?php

declare(strict_types=1);

namespace App\Models\Repositories;

use App\Contracts\GameRepositoryInterface;
use App\Models\Game;
use Illuminate\Database\Eloquent\Collection;

/**
 * GameRepository
 *
 * This class provides functionality to interact with game data within the application.
 */
class GameRepository implements GameRepositoryInterface
{
    /**
     * @param Game $game
     */
    public function __construct(
        private readonly Game $game
    ) {
    }

    /**
     * Retrieve games scheduled for a specific week.
     *
     * @param int $weekId The ID of the week for which to retrieve games.
     *
     * @return Collection Returns a collection of games for the specified week.
     */
    public function getGamesByWeek(int $weekId): Collection
    {
        return $this->game->where('week', $weekId)
                          ->join('teams as home_teams', 'games.home_team_id', '=', 'home_teams.id')
                          ->join('teams as away_teams', 'games.away_team_id', '=', 'away_teams.id')
                          ->get(['games.*', 'home_teams.name as home_team', 'away_teams.name as away_team']);
    }
}
