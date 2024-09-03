<?php

declare(strict_types=1);

namespace App\Contracts;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface GameManagementInterface
 *
 * Defines the contract for game management operations within the application.
 * This includes functionalities such as retrieving games by week, playing a week,
 * resetting the game schedule, and getting information about the current and total weeks.
 */
interface GameManagementInterface
{
    /**
     * Simulate playing all the games for a specific week.
     *
     * @param int $weekId The ID of the week to play.
     * @return Collection Returns an array of results for the games played.
     */
    public function playWeek(int $weekId): Collection;

    /**
     * Reset the entire game schedule to its initial state.
     *
     * @return void
     */
    public function resetSchedule(): void;

    /**
     * Get the current week in the game schedule.
     *
     * @return int Returns the current week as an integer.
     */
    public function getCurrentWeek(): int;

    /**
     * Get the total number of weeks in the game schedule.
     *
     * @return int Returns the total number of weeks as an integer.
     */
    public function getTotalWeeks(): int;
}