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
        Schema::create('patron_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('fines_if_overdue', 8, 2);
            $table->integer('days_allowed');
            $table->integer('materials_allowed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patron_types');
    }
};
