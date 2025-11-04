<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deelnemers', function (Blueprint $table) {
            $table->date('geboorte_datum')->nullable()->after('achternaam');
            $table->time('geboorte_tijd')->nullable()->after('geboorte_datum');
            $table->text('geboorte_plaats')->nullable()->after('geboorte_tijd');
        });
    }

    public function down(): void
    {
        Schema::table('deelnemers', function (Blueprint $table) {
            $table->dropColumn(['geboorte_datum', 'geboorte_tijd', 'geboorte_plaats']);
        });
    }
};
