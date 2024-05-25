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
            // $table->string('accession')->unique();
            $table->string('call_number');
            $table->string('title');
            $table->string('authors');
            $table->string('image_url')->nullable();
            $table->foreignId('location_id')->references('id')->on('locations');
            $table->integer('volume')->nullable();
            $table->string('edition')->nullable();
            $table->integer('pages');
            $table->date('acquired_date');
            $table->text('remarks')->nullable();
            $table->year('copyright');
            $table->string('source_of_fund');
            $table->float('price', 2)->nullable();
            $table->boolean('available')->default(true);
            $table->enum('status', ['available', 'unreturned', 'missing', 'unlabeled'])->default('available');
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
