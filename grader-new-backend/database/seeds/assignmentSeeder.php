<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class assignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('assignments')->insert([
            'crn' => "crn_1",
            'name' => "ICA 1_1",
            'start_date' => "2020-05-20",
            'end_date' => "2020-06-15",
            'tries' => 3,
            'language' => 5
        ]);
        DB::table('assignments')->insert([
            'crn' => "crn_2",
            'name' => "ICA 1_1",
            'start_date' => "2020-05-20",
            'end_date' => "2020-06-15",
            'tries' => 3,
            'language' => 6
        ]);
        DB::table('assignments')->insert([
            'crn' => "crn_3",
            'name' => "ICA 1_1",
            'start_date' => "2020-05-20",
            'end_date' => "2020-06-15",
            'tries' => 3,
            'language' => 7
        ]);
    }
}
