<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('aanmeldingen', function (Blueprint $table) {
            $table->id(); // AUTO_INCREMENT PK

            $table->unsignedInteger('id_deelnemer')->index();
            $table->unsignedInteger('id_training')->index();
            $table->smallInteger('betaal_status');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aanmeldingen');
    }
};
