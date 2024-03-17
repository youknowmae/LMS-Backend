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
        Schema::create('borrow_periodicals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->references('id')->on('borrow_materials');
            $table->foreignId('periodical_id')->references('id')->on('periodicals');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_periodicals');
    }
};
