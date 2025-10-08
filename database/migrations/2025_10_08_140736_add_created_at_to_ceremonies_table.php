<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('ceremonies', 'created_at')) {
            Schema::table('ceremonies', function (Blueprint $table) {
                // géén ->after(): zo komt de kolom vanzelf als laatste te staan
                $table->timestamp('created_at')->nullable()->useCurrent();
            });

            // (optioneel) bestaande NULLs vullen met nu:
            DB::table('ceremonies')->whereNull('created_at')->update(['created_at' => now()]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('ceremonies', 'created_at')) {
            Schema::table('ceremonies', function (Blueprint $table) {
                $table->dropColumn('created_at');
            });
        }
    }
};
