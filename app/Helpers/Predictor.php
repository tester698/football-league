<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Contracts\PredictorInterface;
use App\Models\Game;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Predictor
 *
 * Provides functionality to predict outcomes based on statistical data.
 */
class Predictor implements PredictorInterface
{
    public function __construct(
        private readonly Game $game,
    ) {
    }

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
    public function predict(Collection $stats): array
    {
        $unplayedGames = $this->game->where('is_played', false)->get();
        $remainingNumber = $unplayedGames->count();
        $outcome = 3;// Number of possible outcomes per match (3 outcomes: win, draw, lose)
        $totalOutcomes = pow($outcome, $remainingNumber);// Calculate total possible outcomes for all remaining matches
        $wins = [];
        $points = [];
        foreach ($stats->all() as $stat) {
            $wins[$stat->team_id] = 0;
            $points[$stat->team_id] = $stat->points;
        }
        // Generate all possible outcomes
        for ($i = 0; $i < $totalOutcomes; $i++) {
            $outcomeIndex = $i;
            $simPoints = $points;

            foreach ($unplayedGames as $match) {
                $result = $outcomeIndex % $outcome;
                $outcomeIndex = intdiv($outcomeIndex, $outcome);

                if ($result == 0) { // home win
                    $simPoints[$match->home_team_id] += 3;
                } elseif ($result == 1) { // draw
                    $simPoints[$match->home_team_id] += 1;
                    $simPoints[$match->away_team_id] += 1;
                } else { // guest win
                    $simPoints[$match->away_team_id] += 3;
                }
            }

            // Determine winner
            arsort($simPoints);
            $winner = key($simPoints);
            $wins[$winner]++;
        }

        // Calculate probabilities
        $probabilities = [];
        foreach ($wins as $team => $count) {
            $probabilities[$team] = ($count / $totalOutcomes) * 100;
        }

        return $probabilities;
    }
}