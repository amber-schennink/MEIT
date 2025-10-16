<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntakegesprekkenSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('intakegesprekken')->truncate();

        DB::table('intakegesprekken')->insert([
            ['id'=>24,'id_deelnemer'=>5,'datum'=>'2025-10-15','begin_tijd'=>'12:00:00','eind_tijd'=>'13:00:00','created_at'=>'2025-10-14 13:24:35'],
            ['id'=>26,'id_deelnemer'=>2,'datum'=>'2025-10-28','begin_tijd'=>'10:00:00','eind_tijd'=>'11:00:00','created_at'=>'2025-10-14 14:01:49'],
            ['id'=>27,'id_deelnemer'=>4,'datum'=>'2025-10-15','begin_tijd'=>'15:00:00','eind_tijd'=>'16:00:00','created_at'=>'2025-10-14 14:03:17'],
        ]);
    }
}
