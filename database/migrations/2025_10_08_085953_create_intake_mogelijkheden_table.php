<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('intake_mogelijkheden', function (Blueprint $table) {
            $table->id();                               
            $table->date('datum');                 
            $table->time('begin_tijd');          
            $table->time('eind_tijd');             
            $table->timestamp('created_at')->useCurrent(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intake_mogelijkheden');
    }
};
