<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

/**
 * Interface TeamRepositoryInterface
 *
 * Defines the contract for team management operations.
 * This interface is responsible for declaring methods related to managing teams and their results.
 */
interface TeamRepositoryInterface
{
    /**
     * Get a collection of teams along with their associated results.
     *
     * @return Collection A collection of teams and their results.
     */
    public function getAllWithResults(): Collection;
}