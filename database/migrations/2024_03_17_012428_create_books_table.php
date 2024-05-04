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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('call_number');
            // $table->string('isbn')->nullable();
            $table->string('title');
            $table->string('author');
            $table->string('image_location')->nullable();
            $table->foreignId('location_id')->references('id')->on('locations');
            $table->string('publisher')->nullable();
            $table->integer('volume')->nullable();
            $table->string('edition')->nullable();
            $table->integer('pages');
            $table->date('acquired_date')->nullable();
            $table->text('remarks')->nullable();
            $table->date('copyright');
            $table->string('source_of_fund');
            $table->float('price', 2)->nullable();
            $table->boolean('available')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
