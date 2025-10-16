<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->truncate();

        DB::table('admins')->insert([
            // ongewijzigd
            ['id' => 1, 'email' => 'admin@test.mail',  'wachtwoord' => '$2y$12$Fl5iMTl2ou7J6mycn6NZZuLdbY2wmYRY0Pcks6NJVxTU0Dt02hVYm'],

            // NIEUW: gehashte wachtwoorden voor de door jou opgegeven inlogs
            ['id' => 2, 'email' => 'raphael@admin.nl', 'wachtwoord' => Hash::make('8aPlgAoKA50OnJ')],
            ['id' => 3, 'email' => 'jacelyn@admin.nl', 'wachtwoord' => Hash::make('Dh5NgEj8p6IbMc')],
        ]);
    }
}
