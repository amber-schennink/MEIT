<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ceremonies', function (Blueprint $table) {
          // bedragen in centen
          $table->integer('amount_paid')->default(0);

          // stripe refs
          $table->string('stripe_customer_id', 100)->nullable();
          $table->string('stripe_checkout_session_id', 150)->nullable();
          $table->string('stripe_payment_intent_id', 150)->nullable();
          $table->string('stripe_payment_method_id', 150)->nullable();

          // handig om terug te vinden
          $table->string('customer_email')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ceremonies', function (Blueprint $table) {
          $table->dropColumn([
            'updated_at', 'amount_paid',
            'stripe_customer_id','stripe_checkout_session_id',
            'stripe_payment_intent_id','stripe_payment_method_id',
            'customer_email'
          ]);
        });
    }
};
