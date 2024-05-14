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
            $table->string('title');
            $table->string('authors');
            $table->string('image_url')->nullable();
            $table->foreignId('location_id')->references('id')->on('locations');
            $table->integer('volume')->nullable();
            $table->string('edition')->nullable();
            $table->integer('pages');
            $table->text('blurb');
            $table->date('published_date');
            $table->boolean('isAvailable')->default(true);
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
