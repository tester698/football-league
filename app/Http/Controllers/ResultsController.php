<?php

namespace App\Http\Controllers;

use App\Contracts\GameManagementInterface;
use App\Contracts\GameRepositoryInterface;
use App\Contracts\TeamRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * ResultsController handles game results related operations.
 *
 * This controller is responsible for managing the display and update of game results.
 * It interacts with both the TeamManagement and GameManagement services to retrieve
 * and update game results based on team and game week data.
 */
class ResultsController extends Controller
{
    /**
     * Constructor for ResultsController.
     *
     * Initializes the controller with TeamManagement and GameManagement services.
     * These services are used to manage team results and game schedules respectively.
     *
     * @param TeamRepositoryInterface $teamManagement Service for managing team results.
     * @param GameManagementInterface $gameManagement Service for managing game schedules.
     */
    public function __construct(
        private readonly TeamRepositoryInterface $teamManagement,
        private readonly GameManagementInterface $gameManagement,
        private readonly GameRepositoryInterface $gameRepository
    ) {
    }

    /**
     * Display a listing of all team results.
     *
     * Retrieves and returns a collection of all teams along with their associated results.
     * This method is typically used to display the results of all teams in the system.
     *
     * @return Collection A collection of teams and their results.
     */
    public function index(): Collection
    {
        return $this->teamManagement->getAllWithResults();
    }

    /**
     * Display the game results for a specific week.
     *
     * Retrieves and returns a collection of games and their results for a specified week.
     * This method is useful for filtering results by game week.
     *
     * @param int $weekId The ID of the week for which to retrieve game results.
     * @return Collection A collection of games and their results for the specified week.
     */
    public function show(int $weekId): Collection
    {
        return $this->gameRepository->getGamesByWeek($weekId);
    }

    /**
     * Store or update the game results for a specific week.
     *
     * Processes a request to play out the games for a specified week and update their results.
     * This method is triggered via a POST request with the week ID.
     *
     * @param Request $request The incoming request, containing the 'week_id' to process.
     * @return Collection A collection containing the updated results for the specified week.
     */
    public function store(Request $request): Collection
    {
        $weekId = $request->get('week_id');
        return $this->gameManagement->playWeek($weekId);
    }
}