<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    public function run()
    {
        DB::table('countries')->insert([
            ['id' => 'c1','name' => 'Country One','logo' => ''],
            ['id' => 'c2','name' => 'Country Two','logo' => ''],
        ]);
    }
}
