<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AanmeldingenSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('aanmeldingen')->truncate();

        DB::table('aanmeldingen')->insert([
            [
                'id'=>56,'id_deelnemer'=>2,'id_training'=>31,'betaal_status'=>2,
                'created_at'=>'2025-10-14 10:51:20','updated_at'=>'2025-10-14 10:51:36',
                'amount_paid'=>44400,'amount_due_remaining'=>0,'due_at'=>null,
                'stripe_customer_id'=>'fake_f653b6d136b8bc5e',
                'stripe_checkout_session_id'=>'cs_test_a15VpCcMMaJ4gZukKlDzvjraHcHU54TZOIo0KjcXHm9f8uci2ocwsKUW2W',
                'stripe_payment_intent_id'=>null,'stripe_payment_method_id'=>null,'customer_email'=>'pietjansen@email.nl',
            ],
            [
                'id'=>57,'id_deelnemer'=>3,'id_training'=>31,'betaal_status'=>0,
                'created_at'=>'2025-10-14 11:08:27','updated_at'=>'2025-10-14 11:08:27',
                'amount_paid'=>0,'amount_due_remaining'=>0,'due_at'=>null,
                'stripe_customer_id'=>'fake_167e7c5e96012066',
                'stripe_checkout_session_id'=>null,'stripe_payment_intent_id'=>null,'stripe_payment_method_id'=>null,'customer_email'=>'chrisvanderheiden@email.nl',
            ],
            [
                'id'=>58,'id_deelnemer'=>4,'id_training'=>31,'betaal_status'=>1,
                'created_at'=>'2025-10-14 11:11:08','updated_at'=>'2025-10-14 11:11:08',
                'amount_paid'=>22200,'amount_due_remaining'=>22200,'due_at'=>'2025-10-14 00:00:00',
                'stripe_customer_id'=>'fake_2167522df1471ac9',
                'stripe_checkout_session_id'=>null,'stripe_payment_intent_id'=>null,'stripe_payment_method_id'=>null,'customer_email'=>'geertjanvandepol@email.nl',
            ],
            [
                'id'=>59,'id_deelnemer'=>1,'id_training'=>31,'betaal_status'=>2,
                'created_at'=>'2025-10-14 11:12:23','updated_at'=>'2025-10-14 11:12:23',
                'amount_paid'=>44400,'amount_due_remaining'=>0,'due_at'=>null,
                'stripe_customer_id'=>'fake_3762712cb16c2ea2',
                'stripe_checkout_session_id'=>null,'stripe_payment_intent_id'=>null,'stripe_payment_method_id'=>null,'customer_email'=>'sannemol@email.nl',
            ],
            [
                'id'=>60,'id_deelnemer'=>1,'id_training'=>30,'betaal_status'=>0,
                'created_at'=>'2025-10-14 11:15:23','updated_at'=>'2025-10-14 11:15:23',
                'amount_paid'=>0,'amount_due_remaining'=>0,'due_at'=>null,
                'stripe_customer_id'=>'fake_ae01d3473113ca4b',
                'stripe_checkout_session_id'=>null,'stripe_payment_intent_id'=>null,'stripe_payment_method_id'=>null,'customer_email'=>'sannemol@email.nl',
            ],
            [
                'id'=>61,'id_deelnemer'=>5,'id_training'=>30,'betaal_status'=>1,
                'created_at'=>'2025-10-14 11:18:20','updated_at'=>'2025-10-14 11:18:20',
                'amount_paid'=>22200,'amount_due_remaining'=>22200,'due_at'=>'2025-10-27 00:00:00',
                'stripe_customer_id'=>'fake_5dea458a3756b2cc',
                'stripe_checkout_session_id'=>null,'stripe_payment_intent_id'=>null,'stripe_payment_method_id'=>null,'customer_email'=>'lennybloem@email.com',
            ],
        ]);
    }
}
