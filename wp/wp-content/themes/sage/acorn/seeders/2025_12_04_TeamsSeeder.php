<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamsSeeder extends Seeder
{
    public function run()
    {
        DB::table('teams')->insert([
            ['id' => 't1','competition_id' => 'comp1','country_id' => 'c1','name' => 'Team Alpha','logo' => ''],
            ['id' => 't2','competition_id' => 'comp1','country_id' => 'c2','name' => 'Team Beta','logo' => ''],
            ['id' => 't3','competition_id' => 'comp2','country_id' => 'c1','name' => 'Team Gamma','logo' => ''],
        ]);
    }
}
