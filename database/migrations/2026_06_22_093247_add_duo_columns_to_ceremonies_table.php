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
          $table->boolean('duo')
            ->default(false)
            ->after('pending_deelnemer_id');

          $table->foreignId('id_duo_deelnemer')
            ->nullable()
            ->after('duo')
            ->constrained('duo_deelnemers')
            ->nullOnDelete();

          $table->foreignId('pending_duo_deelnemer_id')
            ->nullable()
            ->after('id_duo_deelnemer')
            ->constrained('duo_deelnemers')
            ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ceremonies', function (Blueprint $table) {
          $table->dropIndex(['id_duo_deelnemer']);
          $table->dropIndex(['pending_duo_deelnemer_id']);

          $table->dropColumn([
            'duo',
            'id_duo_deelnemer',
            'pending_duo_deelnemer_id',
          ]);
        });
    }
};
