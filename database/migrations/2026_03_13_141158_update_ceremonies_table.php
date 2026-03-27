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
            $table->tinyInteger('betaal_status')
                ->nullable()
                ->after('datum') 
                ->change();

            $table->timestamp('updated_at')
                ->nullable()
                ->after('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ceremonies', function (Blueprint $table) {
            $table->dropColumn('updated_at');

            $table->tinyInteger('betaal_status')
                ->after('created_at')
                ->change();
        });
    }
};
