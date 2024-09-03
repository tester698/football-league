<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PredictionManagementInterface;
use App\Contracts\PredictorInterface;
use App\Helpers\Predictor;
use App\Models\Result;
use App\Models\Team;

/**
 * Class PredictionManagement
 *
 * Provides a contract for managing predictions within the application. This includes
 * functionalities such as retrieving an array of predictions.
 */
class PredictionManagement implements PredictionManagementInterface
{
    /**
     * @param Predictor $predictor
     * @param Result $result
     * @param Team $team
     */
    public function __construct(
        private readonly PredictorInterface $predictor,
        private readonly Result $result,
        private readonly Team $team,
    ) {
    }

    /**
     * Retrieve an array of predictions.
     *
     * @return array{{'name'=> <string>, 'prediction' => <float>}} Returns an array of predictions.
     */
    public function getPredictions(): array
    {
        $stats = $this->result->all();
        $predictions = $this->predictor->predict($stats);
        $teams = $this->team->all();
        $teamSorted = [];
        foreach ($teams->all() as $team) {
            $teamSorted[$team->id] = $team;
        }
        $predictionsNamed = [];
        foreach ($predictions as $teamId => $prediction) {
            $team = $teamSorted[$teamId];
            $predictionsNamed[] = ['name' => $team->name, 'prediction' => $prediction];
        }
        return $predictionsNamed;
    }
}
