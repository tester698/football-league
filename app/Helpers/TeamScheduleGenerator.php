<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Contracts\TeamScheduleGeneratorInterface;
use App\Models\Team;

/**
 * Class TeamScheduleGenerator
 *
 * Generates a round-robin schedule for teams stored in the database.
 * This class is responsible for creating a balanced schedule where each team
 * plays against every other team twice, once at home and once away.
 */
class TeamScheduleGenerator implements TeamScheduleGeneratorInterface
{
    /**
     * Constructor for the TeamScheduleGenerator class.
     *
     * @param Team $team An instance of the Team model to interact with the teams table.
     */
    public function __construct(
        private readonly Team $team,
    ) {
    }

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
    public function generateSchedule(): array
    {
        $teams = $this->team->all();
        $totalTeams = count($teams);
        $totalWeeks = ($totalTeams - 1) * 2;
        $schedule = [];

        if ($totalTeams % 2 != 0) {
            throw new \Exception('The number of teams must be even to generate a valid schedule. Number of teams: ' . $totalTeams);
        }

        for ($week = 1; $week <= $totalWeeks; $week++) { // Loop through each week
            $weekSchedule = []; // Initialize the week's schedule
            for ($i = 0; $i < $totalTeams / 2; $i++) { // Loop to generate games for the week
                $home = ($week + $i) % ($totalTeams - 1); // Calculate the home team index
                $away = ($totalTeams - 1 - $i + $week) % ($totalTeams - 1); // Calculate the away team index

                if ($i == 0) { // Ensure the last team plays in every week
                    $away = $totalTeams - 1;
                }

                if ($week > ($totalTeams - 1)) { // Swap home and away teams for the second half of the schedule
                    list($home, $away) = [$away, $home];
                }

                $weekSchedule[] =
                    ['home' => $teams[$home], 'away' => $teams[$away]]; // Add the game to the week's schedule
            }
            $schedule[] = $weekSchedule; // Add the week's schedule to the overall schedule
        }

        return $schedule;
    }
}