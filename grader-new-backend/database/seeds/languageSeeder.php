<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class languageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->insert([
            'language' => 'GNU GCC C11 5.1.0',
        ]);
        DB::table('languages')->insert([
            'language' => "Phyton 3.7.2",
        ]);
        DB::table('languages')->insert([
            'language' => "Java 11.0.6",
        ]);
    }
}
