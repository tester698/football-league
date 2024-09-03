<?php

namespace App\Providers;

use App\Contracts\GameManagementInterface;
use App\Contracts\GamePlaySimulatorInterface;
use App\Contracts\GameRepositoryInterface;
use App\Contracts\PredictionManagementInterface;
use App\Contracts\PredictorInterface;
use App\Contracts\StatisticsManagerInterface;
use App\Contracts\TeamRepositoryInterface;
use App\Contracts\TeamScheduleGeneratorInterface;
use App\Helpers\GamePlaySimulator;
use App\Helpers\Predictor;
use App\Helpers\StatisticsManager;
use App\Helpers\TeamScheduleGenerator;
use App\Models\Repositories\GameRepository;
use App\Models\Repositories\TeamRepository;
use App\Services\GameManagement;
use App\Services\PredictionManagement;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GameManagementInterface::class, GameManagement::class);
        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
        $this->app->bind(PredictionManagementInterface::class, PredictionManagement::class);
        $this->app->bind(GamePlaySimulatorInterface::class, GamePlaySimulator::class);
        $this->app->bind(PredictorInterface::class, Predictor::class);
        $this->app->bind(StatisticsManagerInterface::class, StatisticsManager::class);
        $this->app->bind(TeamScheduleGeneratorInterface::class, TeamScheduleGenerator::class);
        $this->app->bind(GameRepositoryInterface::class, GameRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
