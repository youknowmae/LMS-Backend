<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('opac_view_counts', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique()->default(DB::raw('CURRENT_DATE'));  //di gumagana useCurrent() T.T
            $table->integer('view_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opac_view_counts');
    }
};
