<?php
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(\CountriesSeeder::class);
        $this->call(\CompetitionsSeeder::class);
        $this->call(\TeamsSeeder::class);
        $this->call(\MatchesSeeder::class);
    }
}
