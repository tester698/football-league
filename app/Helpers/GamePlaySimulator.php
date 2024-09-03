<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Contracts\GamePlaySimulatorInterface;
use App\Contracts\StatisticsManagerInterface;
use App\Models\Game;

/**
 * Class GamePlaySimulator
 *
 * Simulates the gameplay for a specific week by updating game results in the database.
 * This class is responsible for fetching games scheduled for a given week,
 * simulating the outcomes, and persisting these outcomes to the database.
 */
class GamePlaySimulator implements GamePlaySimulatorInterface
{
    /**
     * Constructor for the GamePlaySimulator class.
     *
     * @param Game $game An instance of the Game model to interact with the games table.
     */
    public function __construct(
        private readonly Game $game,
        private readonly StatisticsManagerInterface $statisticsManager,
    ) {
    }

    /**
     * Simulates playing the games for a specified week.
     *
     * This method retrieves all games scheduled for the given week, simulates the results
     * by randomly assigning goals to home and away teams, and updates the game records
     * in the database to reflect these simulated outcomes.
     *
     * @param int $weekId The ID of the week for which games are to be simulated.
     *
     * @return void
     */
    public function playWeek(int $weekId): void
    {
        $games = $this->game->where('week', $weekId)->get();
        foreach ($games as $game) {
            $game->is_played = true;
            $game->home_team_goals = rand(0, 5);
            $game->away_team_goals = rand(0, 5);
            $game->save();
        }
        $this->statisticsManager->updateStats();
    }
}