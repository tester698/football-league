<?php

/**
 * Interface for Prediction Management
 *
 * Provides a contract for managing predictions within the application. This includes
 * functionalities such as retrieving an array of predictions.
 *
 */
declare(strict_types=1);

namespace App\Contracts;

/**
 * Interface PredictionManagementInterface
 *
 * Defines the contract for prediction management operations. This includes
 * retrieving an array of predictions.
 */
interface PredictionManagementInterface
{
    /**
     * Retrieve an array of predictions.
     *
     * @return array{{'name'=> <string>, 'prediction' => <float>}} Returns an array of predictions.
     */
    public function getPredictions(): array;
}