<?php

namespace App\Http\Controllers;

use App\Contracts\GameManagementInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * ResetController handles game reset operations.
 *
 * This controller is responsible for resetting the game schedule to its initial state.
 * It interacts with the GameManagement service to perform the reset operation.
 */
class ResetController extends Controller
{
    /**
     * Create a new ResetController instance.
     *
     * Initializes the controller with a game management service to handle game reset operations.
     *
     * @param GameManagementInterface $gameManagement The game management service.
     */
    public function __construct(
        private readonly GameManagementInterface $gameManagement,
    ) {
    }

    /**
     * Store a newly created resource in storage.
     *
     * Resets the game schedule to its initial state and returns a JSON response indicating success.
     * This method is typically triggered via a POST request.
     *
     * @param Request $request The incoming request.
     *
     * @return JsonResponse A JSON response indicating the status of the reset operation.
     */
    public function store(Request $request): JsonResponse
    {
        $this->gameManagement->resetSchedule();
        return response()->json(['status' => "OK"]);
    }
}