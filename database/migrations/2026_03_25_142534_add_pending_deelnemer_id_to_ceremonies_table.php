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
          $table->foreignId('pending_deelnemer_id')
            ->nullable()
            ->after('id_deelnemer')
            ->constrained('deelnemers')
            ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ceremonies', function (Blueprint $table) {
          $table->dropConstrainedForeignId('pending_deelnemer_id');
        });
    }
};
