<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetitionsSeeder extends Seeder
{
    public function run()
    {
        DB::table('competitions')->insert([
            ['id' => 'comp1','name' => 'Premier League','logo' => ''],
            ['id' => 'comp2','name' => 'Champions Cup','logo' => ''],
        ]);
    }
}
