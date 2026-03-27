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
      Schema::dropIfExists('intake_mogelijkheden');
      Schema::dropIfExists('intakegesprekken');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::create('intakegesprekken', function (Blueprint $table) {
        $table->id();
        $table->unsignedInteger('id_deelnemer');
        $table->date('datum');
        $table->time('begin_tijd');
        $table->time('eind_tijd');
        $table->timestamp('created_at')->useCurrent();
      });

      Schema::create('intake_mogelijkheden', function (Blueprint $table) {
        $table->id();
        $table->date('datum');
        $table->time('begin_tijd');
        $table->time('eind_tijd');
        $table->timestamp('created_at')->useCurrent();
      });
    }
};
