<?php

declare(strict_types=1);

namespace App\Models\Repositories;

use App\Contracts\TeamRepositoryInterface;
use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TeamManagement
 *
 *  Defines the contract for team management operations.
 *  This class is responsible for declaring methods related to managing teams and their results.
 */
class TeamRepository implements TeamRepositoryInterface
{
    /**
     * @param Team $team
     */
    public function __construct(
        private readonly Team $team
    ) {
    }

    /**
     * Get a collection of teams along with their associated results.
     *
     * @return Collection A collection of teams and their results.
     */
    public function getAllWithResults(): Collection
    {
        return $this->team->join('results', 'teams.id', '=', 'results.team_id')
                          ->get(
                              [
                                  'teams.*',
                                  'results.points as points',
                                  'results.wins as matchesWon',
                                  'results.draws as matchesDrawn',
                                  'results.losses as matchesLost',
                                  'results.played as matchesPlayed',
                                  'results.goals_for as goalsFor',
                                  'results.goals_against as goalsAgainst',
                                  'results.goal_difference as goalDifference',
                              ]
                          );
    }
}
