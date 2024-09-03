<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class Predictor
 *
 * Provides functionality to predict outcomes based on statistical data.
 */
interface PredictorInterface
{
    /**
     * Predicts the outcome of unplayed games based on the provided statistics.
     *
     * This method generates all possible outcomes for the remaining unplayed games
     * and calculates the probability of each team winning the league based on the
     * generated outcomes.
     *
     * @param Collection $stats A collection of team statistics.
     *
     * @return array<int, float> An associative array where the key is the team_id and the value is the probability of
     *     the team winning the league.
     */
    public function predict(Collection $stats): array;
}