<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Team;

/**
 * Class TeamScheduleGenerator
 *
 * Generates a round-robin schedule for teams stored in the database.
 * This class is responsible for creating a balanced schedule where each team
 * plays against every other team twice, once at home and once away.
 */
interface TeamScheduleGeneratorInterface
{
    /**
     * Generates a round-robin schedule for all teams.
     *
     * This method calculates a schedule where each team plays against every other
     * team twice, ensuring a fair and balanced competition. The schedule is
     * returned as an array of weeks, with each week containing pairs of teams
     * that will play against each other.
     *
     * @return array<int, array<int, array{home: Team, away: Team}>> $schedule The generated schedule as an array of
     *     weeks, each week is an array of games.
     */
    public function generateSchedule(): array;
}