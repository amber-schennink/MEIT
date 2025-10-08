<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deelnemers', function (Blueprint $table) {
            $table->id();               

            $table->text('voornaam');       
            $table->text('tussenvoegsel')->nullable();
            $table->text('achternaam');     
            $table->text('email');             
            $table->text('telefoon_nummer')->nullable();
            $table->text('wachtwoord');         
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deelnemers');
    }
};
