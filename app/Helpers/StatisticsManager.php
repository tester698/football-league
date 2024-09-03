<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Contracts\StatisticsManagerInterface;
use App\Models\Game;
use App\Models\Result;
use App\Models\Team;

/**
 * Class StatisticsManager
 *
 * Manages the statistics of teams based on game results. This includes clearing
 * existing statistics and updating them based on the outcomes of played games.
 */
class StatisticsManager implements StatisticsManagerInterface
{
    /**
     * @param Team $team
     * @param Result $result
     * @param Game $game
     */
    public function __construct(
        private readonly Team $team,
        private readonly Result $result,
        private readonly Game $game
    ) {
    }

    /**
     * Clears the existing statistics for all teams.
     *
     * This method resets all team statistics to zero, preparing for the
     * recalculation of statistics based on the outcomes of played games.
     *
     * @return void
     */
    public function clearStats(): void
    {
        $teams = $this->team->all();
        $results = [];
        foreach ($teams as $team) {
            $teamResults = [
                'team_id' => $team->id,
                'id' => $team->results->id,
                'points' => 0,
                'wins' => 0,
                'draws' => 0,
                'losses' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'goal_difference' => 0,
                'played' => 0,
            ];
            $results[] = $teamResults;
        }
        $this->result->upsert(
            $results,
            ['id', 'team_id'],
            ['points', 'wins', 'draws', 'losses', 'goals_for', 'goals_against', 'goal_difference', 'played']
        );
    }

    /**
     * Updates the statistics for all teams based on game results.
     *
     * This method recalculates the statistics for all teams based on the outcomes
     * of played games. It updates the points, wins, draws, losses, goals for, goals against,
     * goal difference, and number of games played for each team.
     *
     * @return void
     */
    public function updateStats(): void
    {
        $this->result->truncate();
        $games = $this->game->where('is_played', true)->get();
        $stats = [];

        foreach ($games as $game) {
            if (!isset($stats[$game->home_team_id])) {
                $stats[$game->home_team_id] = $this->initializeStats($game->home_team_id);
            }
            if (!isset($stats[$game->away_team_id])) {
                $stats[$game->away_team_id] = $this->initializeStats($game->away_team_id);
            }

            // Update stats based on match results
            $this->updateTeamStats($stats[$game->home_team_id], $game->home_team_goals, $game->away_team_goals);
            $this->updateTeamStats($stats[$game->away_team_id], $game->away_team_goals, $game->home_team_goals);
        }

        $this->result->factory()->createMany($stats);
    }

    /**
     * Initializes the statistics for a team.
     *
     * This helper method creates a new array of statistics with all values set to zero.
     * It is used to prepare the statistics for a team before updating them based on game outcomes.
     *
     * @param int $teamId The ID of the team for which to initialize statistics.
     *
     * @return array{
     *     team_id: int,
     *     points: int,
     *     wins: int,
     *      draws: int,
     *      losses: int,
     *      goals_for: int,
     *      goals_against: int,
     *      goal_difference: int,
     *      played: int
     * }
     */
    private function initializeStats(int $teamId): array
    {
        return [
            'team_id' => $teamId,
            'points' => 0,
            'wins' => 0,
            'draws' => 0,
            'losses' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'goal_difference' => 0,
            'played' => 0,
        ];
    }

    /**
     * Updates the statistics for a team based on game results.
     *
     * This method takes the current statistics for a team and updates them based on the
     * goals scored and conceded in a game. It increments the number of games played,
     * updates the goals for and against, calculates the goal difference, and updates
     * the points, wins, draws, and losses based on the game outcome.
     *
     * @param array $teamStats The current statistics for the team.
     * @param int $goalsFor The number of goals scored by the team.
     * @param int $goalsAgainst The number of goals conceded by the team.
     *
     * @return void
     */
    public function updateTeamStats(array &$teamStats, int $goalsFor, int $goalsAgainst): void
    {
        $teamStats['played']++;
        $teamStats['goals_for'] += $goalsFor;
        $teamStats['goals_against'] += $goalsAgainst;
        $teamStats['goal_difference'] = $teamStats['goals_for'] - $teamStats['goals_against'];

        if ($goalsFor > $goalsAgainst) {
            $teamStats['wins']++;
            $teamStats['points'] += 3;
        } elseif ($goalsFor == $goalsAgainst) {
            $teamStats['draws']++;
            $teamStats['points'] += 1;
        } else {
            $teamStats['losses']++;
        }
    }
}
