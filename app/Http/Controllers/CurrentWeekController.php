<?php

namespace App\Http\Controllers;

use App\Contracts\GameManagementInterface;
use Illuminate\Http\JsonResponse;

/**
 * Handles operations related to the current week within the game.
 *
 * This controller is responsible for managing and displaying information
 * about the current week in the game, such as retrieving the current week number
 * and the total number of weeks.
 */
class CurrentWeekController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param GameManagementInterface $gameManagement The game management service.
     */
    public function __construct(
        private readonly GameManagementInterface $gameManagement,
    ) {
    }

    /**
     * Display the current week and the maximum number of weeks.
     *
     * @return JsonResponse Returns a JSON response containing the current week number
     *                                       and the total number of weeks in the game.
     */
    public function index(): JsonResponse
    {
        $currentWeek = $this->gameManagement->getCurrentWeek();
        $maxWeek = $this->gameManagement->getTotalWeeks();
        return response()->json(['current_week' => $currentWeek ?: 0, 'max_week' => $maxWeek]);
    }
}