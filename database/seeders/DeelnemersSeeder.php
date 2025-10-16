<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DeelnemersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('deelnemers')->truncate();

        DB::table('deelnemers')->insert([
            ['id'=>1,'voornaam'=>'Sanne','tussenvoegsel'=>null,'achternaam'=>'Mol','email'=>'sannemol@email.nl','telefoon_nummer'=>null,'wachtwoord'=>'$2y$12$JxdV4QzmShAdlQCzu2inE.Pevx977hkKbTsHfyL6WwsI1xzWdNuPW','created_at'=>null,'updated_at'=>'2025-10-14 11:04:00'],
            ['id'=>2,'voornaam'=>'Piet','tussenvoegsel'=>null,'achternaam'=>'Jansen','email'=>'pietjansen@email.com','telefoon_nummer'=>null,'wachtwoord'=>'$2y$12$vXWjGt4MTo8Y2dLF8sTcGep2Sg2l/YZIBtgdyvYRidlQNgf8SC7bi','created_at'=>null,'updated_at'=>'2025-10-14 12:01:35'],
            ['id'=>3,'voornaam'=>'Christien','tussenvoegsel'=>'van der','achternaam'=>'Heiden','email'=>'chrisvanderheiden@email.nl','telefoon_nummer'=>null,'wachtwoord'=>'$2y$12$J9VUwWviRge6TKrKeKsxcuTr5gyUFBnyntx.sSWI.6p6LfLbv.7Xi','created_at'=>null,'updated_at'=>'2025-10-14 12:02:26'],
            ['id'=>4,'voornaam'=>'Geert-Jan','tussenvoegsel'=>'van de','achternaam'=>'Pol','email'=>'geertjanvandepol@email.nl','telefoon_nummer'=>'06-12345678','wachtwoord'=>'$2y$12$j3H3ptJK2FueGF1v7tMpdOU367Mstr0Yw/8LaR4hTuDqArM0rn4UG','created_at'=>null,'updated_at'=>'2025-10-14 12:02:51'],
            ['id'=>5,'voornaam'=>'Lenny','tussenvoegsel'=>null,'achternaam'=>'Bloem','email'=>'lennybloem@email.com','telefoon_nummer'=>'06-12345678','wachtwoord'=>'$2y$12$3DCGMfiaccVNBgvYBmwGK.PxXVhjFwxCxKLZyUMaeaMh7VAm8xkS2','created_at'=>null,'updated_at'=>'2025-10-14 12:04:32'],

            // NIEUW: gehashte wachtwoorden voor de door jou opgegeven inlogs
            ['id'=>6,'voornaam'=>'Raphael','tussenvoegsel'=>null,'achternaam'=>'EazyOnline','email'=>'raphael@deelnemer.nl','telefoon_nummer'=>null,'wachtwoord'=>Hash::make('3MEj90GJg1In7m'),'created_at'=>null,'updated_at'=>'2025-10-14 12:00:42'],
            // Let op: trailing spatie uit jouw dump verwijderd in het e-mailadres hieronder
            ['id'=>7,'voornaam'=>'Jacelyn','tussenvoegsel'=>null,'achternaam'=>'Blok','email'=>'jacelyn@meit.nl','telefoon_nummer'=>null,'wachtwoord'=>Hash::make('hK0Bdngc24oCN8'),'created_at'=>null,'updated_at'=>'2025-10-15 10:35:40'],
        ]);
    }
}
