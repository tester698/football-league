<?php

declare(strict_types=1);

namespace App\Contracts;

/**
 * Class GamePlaySimulator
 *
 * Simulates the gameplay for a specific week by updating game results in the database.
 * This class is responsible for fetching games scheduled for a given week,
 * simulating the outcomes, and persisting these outcomes to the database.
 */
interface GamePlaySimulatorInterface
{
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
    public function playWeek(int $weekId): void;
}