<?php

declare(strict_types=1);

namespace App\Contracts;

/**
 * Class StatisticsManager
 *
 * Manages the statistics of teams based on game results. This includes clearing
 * existing statistics and updating them based on the outcomes of played games.
 */
interface StatisticsManagerInterface
{
    /**
     * Clears the existing statistics for all teams.
     *
     * This method resets all team statistics to zero, preparing for the
     * recalculation of statistics based on the outcomes of played games.
     *
     * @return void
     */
    public function clearStats(): void;

    /**
     * Updates the statistics for all teams based on game results.
     *
     * This method recalculates the statistics for all teams based on the outcomes
     * of played games. It updates the points, wins, draws, losses, goals for, goals against,
     * goal difference, and number of games played for each team.
     *
     * @return void
     */
    public function updateStats(): void;

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
    public function updateTeamStats(array &$teamStats, int $goalsFor, int $goalsAgainst): void;
}