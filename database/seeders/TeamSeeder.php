<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('teams')->insert(
            [
                [
                    'name' => 'Chelsea',
                    'city' => 'London',
                ],
                [
                    'name' => 'Manchester United',
                    'city' => 'Manchester',
                ],
                [
                    'name' => 'Liverpool',
                    'city' => 'Liverpool',
                ],
                [
                    'name' => 'Arsenal',
                    'city' => 'London',
                ],
            ]
       );
    }
}
