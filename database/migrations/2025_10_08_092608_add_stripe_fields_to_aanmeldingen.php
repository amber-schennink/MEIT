<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::table('aanmeldingen', function (Blueprint $t) {
            // bedragen in centen
            $t->integer('amount_paid')->default(0);
            $t->integer('amount_due_remaining')->default(0);
            $t->dateTime('due_at')->nullable();

            // stripe refs
            $t->string('stripe_customer_id', 100)->nullable();
            $t->string('stripe_checkout_session_id', 150)->nullable();
            $t->string('stripe_payment_intent_id', 150)->nullable();
            $t->string('stripe_payment_method_id', 150)->nullable();

            // handig om terug te vinden
            $t->string('customer_email')->nullable();

            // je had alleen created_at — voeg updated_at toe
            if (!Schema::hasColumn('aanmeldingen', 'updated_at')) {
                $t->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down(): void {
        Schema::table('aanmeldingen', function (Blueprint $t) {
            $t->dropColumn([
                'amount_paid','amount_due_remaining','due_at',
                'stripe_customer_id','stripe_checkout_session_id',
                'stripe_payment_intent_id','stripe_payment_method_id',
                'customer_email','updated_at'
            ]);
        });
    }
};
