<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class studentGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('student_group')->insert([
            'user_id' => "A01730646",
            'crn' => "crn_1"
        ]);
        DB::table('student_group')->insert([
            'user_id' => "A01730646",
            'crn' => "crn_3"
        ]);
        DB::table('student_group')->insert([
            'user_id' => "A01730716",
            'crn' => "crn_1"
        ]);
        DB::table('student_group')->insert([
            'user_id' => "A01730716",
            'crn' => "crn_2"
        ]);
        DB::table('student_group')->insert([
            'user_id' => "A01329173",
            'crn' => "crn_2"
        ]);
        DB::table('student_group')->insert([
            'user_id' => "A01329173",
            'crn' => "crn_3"
        ]);
    }
}
