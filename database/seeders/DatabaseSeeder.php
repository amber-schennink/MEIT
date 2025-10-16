<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminsSeeder::class,
            DeelnemersSeeder::class,
            TrainingenSeeder::class,
            AanmeldingenSeeder::class,
            CeremoniesSeeder::class,
            IntakeMogelijkhedenSeeder::class,
            IntakegesprekkenSeeder::class,
        ]);

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
