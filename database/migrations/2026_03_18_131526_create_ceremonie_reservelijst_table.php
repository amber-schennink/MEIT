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
        Schema::create('ceremonie_reservelijst', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_deelnemer')->index();
            $table->unsignedInteger('id_ceremonie')->index();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ceremonie_reservelijst');
    }
};
