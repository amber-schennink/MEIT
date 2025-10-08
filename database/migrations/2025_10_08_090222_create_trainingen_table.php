<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trainingen', function (Blueprint $table) {
            $table->id();                    // AUTO_INCREMENT PK
            $table->dateTime('start_moment');    // NOT NULL
            $table->dateTime('start_moment_2');  // NOT NULL
            $table->dateTime('start_moment_3');  // NOT NULL
            $table->dateTime('start_moment_4');  // NOT NULL

            // geen created_at/updated_at
            // (optioneel) $table->index('start_moment');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainingen');
    }
};
