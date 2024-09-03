<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\GameManagementInterface;
use App\Contracts\GamePlaySimulatorInterface;
use App\Contracts\StatisticsManagerInterface;
use App\Contracts\TeamScheduleGeneratorInterface;
use App\Models\Game;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class GameManagement
 *
 * Defines the contract for game management operations within the application.
 * This includes functionalities such as retrieving games by week, playing a week,
 * resetting the game schedule, and getting information about the current and total weeks.
 */
readonly class GameManagement implements GameManagementInterface
{
    /**
     * @param TeamScheduleGeneratorInterface $scheduler
     * @param GamePlaySimulatorInterface $simulator
     * @param StatisticsManagerInterface $statisticsManager
     * @param Game $game
     */
    public function __construct(
        private readonly TeamScheduleGeneratorInterface $scheduler,
        private readonly GamePlaySimulatorInterface $simulator,
        private readonly StatisticsManagerInterface $statisticsManager,
        private readonly Game $game
    ) {
    }



    /**
     * Simulate playing all the games for a specific week.
     *
     * @param int $weekId The ID of the week to play.
     *
     * @return Collection Returns an array of results for the games played.
     */
    public function playWeek(int $weekId): Collection
    {
        $this->simulator->playWeek($weekId);
        return $this->game->where('week', $weekId)->get();
    }

    /**
     * Reset the entire game schedule to its initial state.
     *
     * @return void
     */
    public function resetSchedule(): void
    {
        $weeks = $this->scheduler->generateSchedule();
        $this->statisticsManager->updateStats();
        $games = [];
        foreach ($weeks as $i => $week) {
            foreach ($week as $match) {
                $game = [];
                $game['home_team_id'] = $match['home']->id;
                $game['away_team_id'] = $match['away']->id;
                $game['home_team_goals'] = 0;
                $game['away_team_goals'] = 0;
                $game['week'] = $i + 1;
                $game['is_played'] = false;
                $games[] = $game;
            }
        }
        $this->game->truncate();
        $this->game->factory()->createMany($games);
    }

    /**
     * Get the current week in the game schedule.
     *
     * @return int Returns the current week as an integer.
     */
    public function getCurrentWeek(): int
    {
        return $this->game->where('is_played', true)->max('week')?? 0 ;
    }

    /**
     * Get the total number of weeks in the game schedule.
     *
     * @return int Returns the total number of weeks as an integer.
     */
    public function getTotalWeeks(): int
    {
        $max =  $this->game->max('week');
        if (!$max) {
            $this->resetSchedule();
            $max = $this->game->max('week');
        }
        return $max;
    }
}
