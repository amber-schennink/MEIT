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
        Schema::create('duo_deelnemers', function (Blueprint $table) {
            $table->id();

            $table->text('voornaam');
            $table->text('tussenvoegsel')->nullable();
            $table->text('achternaam');

            $table->date('geboorte_datum')->nullable();
            $table->time('geboorte_tijd')->nullable();
            $table->text('geboorte_plaats')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duo_deelnemers');
    }
};
