<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CeremoniesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ceremonies')->truncate();

        DB::table('ceremonies')->insert([
            ['id'=>8,'id_deelnemer'=>1,'datum'=>'2025-10-16','created_at'=>'2025-10-14 13:36:55'],
        ]);
    }
}
