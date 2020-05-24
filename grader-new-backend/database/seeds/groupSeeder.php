<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class groupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert([
            'crn' => "crn_1",
            'name' => "Fundamentos de programación",
            'term_code' => "term_code_1",
            'professor_id' => "L00000001"
        ]);
        DB::table('groups')->insert([
            'crn' => "crn_2",
            'name' => "Progrmacion orientada a objetos",
            'term_code' => "term_code_2",
            'professor_id' => "L00000001"
        ]);
        DB::table('groups')->insert([
            'crn' => "crn_3",
            'name' => "Estructura de datos",
            'term_code' => "term_code_3",
            'professor_id' => "L00000001"
        ]);
    }
}
