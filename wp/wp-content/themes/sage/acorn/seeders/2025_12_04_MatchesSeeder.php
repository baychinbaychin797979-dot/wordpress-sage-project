<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatchesSeeder extends Seeder
{
    public function run()
    {
        DB::table('matches')->insert([
            ['id' => 'm1','competition_id' => 'comp1','home_team_id' => 't1','away_team_id' => 't2','status_id'=>8,'match_time'=>1694095322,'home_scores'=>json_encode([1,0,0,0,-1,0,0]),'away_scores'=>json_encode([0,0,0,0,-1,0,0])],
            ['id' => 'm2','competition_id' => 'comp2','home_team_id' => 't3','away_team_id' => 't1','status_id'=>1,'match_time'=>1694195322,'home_scores'=>json_encode([0,0,0,0,-1,0,0]),'away_scores'=>json_encode([0,0,0,0,-1,0,0])],
        ]);
    }
}
