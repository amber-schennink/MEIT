<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntakeMogelijkhedenSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('intake_mogelijkheden')->truncate();

        DB::table('intake_mogelijkheden')->insert([
            ['id'=>36,'datum'=>'2025-10-23','begin_tijd'=>'14:00:00','eind_tijd'=>'20:00:00','created_at'=>'2025-10-14 13:24:11'],
            ['id'=>37,'datum'=>'2025-10-15','begin_tijd'=>'10:00:00','eind_tijd'=>'12:00:00','created_at'=>'2025-10-14 13:24:35'],
            ['id'=>40,'datum'=>'2025-11-02','begin_tijd'=>'10:00:00','eind_tijd'=>'18:00:00','created_at'=>'2025-10-14 13:30:56'],
            ['id'=>41,'datum'=>'2025-11-05','begin_tijd'=>'13:00:00','eind_tijd'=>'15:15:00','created_at'=>'2025-10-14 13:31:21'],
            ['id'=>42,'datum'=>'2025-10-18','begin_tijd'=>'10:15:00','eind_tijd'=>'13:00:00','created_at'=>'2025-10-14 13:32:24'],
            ['id'=>43,'datum'=>'2025-10-28','begin_tijd'=>'08:00:00','eind_tijd'=>'10:00:00','created_at'=>'2025-10-14 14:01:49'],
            ['id'=>44,'datum'=>'2025-10-28','begin_tijd'=>'11:00:00','eind_tijd'=>'16:00:00','created_at'=>'2025-10-14 14:01:49'],
            ['id'=>45,'datum'=>'2025-10-15','begin_tijd'=>'13:00:00','eind_tijd'=>'15:00:00','created_at'=>'2025-10-14 14:03:17'],
            ['id'=>46,'datum'=>'2025-10-15','begin_tijd'=>'16:00:00','eind_tijd_tijd'=>'17:00:00','created_at'=>'2025-10-14 14:03:17'],
        ]);
    }
}
