<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingenSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('trainingen')->truncate();

        DB::table('trainingen')->insert([
            ['id'=>30,'start_moment'=>'2025-11-03 15:00:00','start_moment_2'=>'2025-11-10 11:45:00','start_moment_3'=>'2025-11-17 12:30:00','start_moment_4'=>'2025-11-24 16:00:00'],
            ['id'=>31,'start_moment'=>'2025-10-21 14:00:00','start_moment_2'=>'2025-10-29 12:00:00','start_moment_3'=>'2025-11-07 14:00:00','start_moment_4'=>'2025-11-12 09:00:00'],
            ['id'=>32,'start_moment'=>'2025-12-04 14:00:00','start_moment_2'=>'2025-12-11 14:00:00','start_moment_3'=>'2025-12-18 14:00:00','start_moment_4'=>'2025-12-25 14:00:00'],
            ['id'=>33,'start_moment'=>'2025-10-13 11:00:00','start_moment_2'=>'2025-10-23 10:00:00','start_moment_3'=>'2025-11-01 16:00:00','start_moment_4'=>'2025-11-27 08:00:00'],
        ]);
    }
}
