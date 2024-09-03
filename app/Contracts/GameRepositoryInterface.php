<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

/**
 * Interface GameRepositoryInterface
 *
 *  This interface provides functionality to interact with game data within the application.
 */
interface GameRepositoryInterface
{
    /**
     * Retrieve games scheduled for a specific week.
     *
     * @param int $weekId The ID of the week for which to retrieve games.
     *
     * @return Collection Returns a collection of games for the specified week.
     */
    public function getGamesByWeek(int $weekId): Collection;
}