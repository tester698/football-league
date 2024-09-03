<?php

namespace Tests\Unit\Helpers;

use App\Helpers\TeamScheduleGenerator;
use App\Models\Team;
use Tests\TestCase;

class TeamScheduleGeneratorTest extends TestCase
{
    private Team $team;

    private TeamScheduleGenerator $helper;

    protected function setUp(): void
    {
        $this->team = \Mockery::mock(Team::class);
        $this->helper = new TeamScheduleGenerator($this->team);
        parent::setUp();
    }

    public function test_generate_schedule_with_even_number_of_teams()
    {
        $teams = collect([
            \Mockery::mock(Team::class),
            \Mockery::mock(Team::class),
            \Mockery::mock(Team::class),
            \Mockery::mock(Team::class),
        ]);

        $this->team->shouldReceive('all')->once()->andReturn($teams);

        $schedule = $this->helper->generateSchedule();

        $this->assertCount(6, $schedule); // Each team plays 6 games
        $this->assertCount(2, $schedule[0]); // 2 games per week
    }

    public function test_generate_schedule_with_odd_number_of_teams()
    {
        $teams = collect([
            \Mockery::mock(Team::class),
            \Mockery::mock(Team::class),
            \Mockery::mock(Team::class),
        ]);

        $this->team->shouldReceive('all')->once()->andReturn($teams);
        $this->expectException(\Exception::class);

        $this->helper->generateSchedule();


    }

    public function test_generate_schedule_with_no_teams()
    {
        $teams = collect([]);

        $this->team->shouldReceive('all')->once()->andReturn($teams);

        $schedule = $this->helper->generateSchedule();

        $this->assertEmpty($schedule); // No games can be scheduled without teams
    }
}
