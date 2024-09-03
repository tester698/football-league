<?php

namespace App\Http\Controllers;

use App\Contracts\PredictionManagementInterface;

/**
 * Handles prediction-related operations.
 *
 * This controller is responsible for managing the interactions related to predictions,
 * such as listing all available predictions. It utilizes the PredictionManagementInterface
 * to interact with the underlying prediction data.
 */
class PredictionController extends Controller
{
    /**
     * Create a new PredictionController instance.
     *
     * Initializes the controller with a prediction management service.
     *
     * @param PredictionManagementInterface $predictionManagement The prediction management service.
     */
    public function __construct(
        private readonly PredictionManagementInterface $predictionManagement,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * Retrieves and returns a list of predictions. Each prediction includes a name and a prediction value.
     * The return type is an array of associative arrays, each containing a 'name' (string) and 'prediction' (float).
     *
     * @return array{{'name'=> string, 'prediction' => float}} List of predictions.
     */
    public function index(): array
    {
        return $this->predictionManagement->getPredictions();
    }
}