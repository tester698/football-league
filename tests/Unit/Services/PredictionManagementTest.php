<?php

namespace Tests\Unit\Services;

use App\Helpers\Predictor;
use App\Models\Result;
use App\Models\Team;
use App\Services\PredictionManagement;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class PredictionManagementTest extends TestCase
{
    private Predictor $predictor;

    private PredictionManagement $service;

    private Result $result;

    private Team $team;

    protected function setUp(): void
    {
        $this->result = \Mockery::mock(Result::class);
        $this->team = \Mockery::mock(Team::class);
        $this->predictor = \Mockery::mock(Predictor::class);
        $this->service = new PredictionManagement($this->predictor, $this->result, $this->team);
        parent::setUp();
    }

    /**
     * A basic unit test example.
     */
    public function test_get_predictions(): void
    {
        $stats = $this->createMock(Collection::class);
        $this->result->shouldReceive('all')->andReturn($stats);
        $predictions = [
            1 => 50,
        ];
        $localTeam = \Mockery::mock(Team::class)->shouldReceive('getAttribute')->with('id')->andReturn(1)->getMock();
        $localTeam->shouldReceive('getAttribute')->with('name')->andReturn('Local Team');
        $teams = \Mockery::mock(Collection::class)->shouldReceive('all')->andReturn([$localTeam])->getMock();
        $this->team->shouldReceive('all')->andReturn($teams);
        $this->predictor->shouldReceive('predict')->with($stats)->andReturn($predictions);
        $result = [
            ['name' => 'Local Team', 'prediction' => 50]
        ];
        $this->assertEquals($result, $this->service->getPredictions());
    }
}
