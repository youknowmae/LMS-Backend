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
        Schema::create('periodicals', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->foreignId('category_id')->references('id')->on('categories');
            $table->string('material_type');
            $table->string('language');
            $table->string('image');
            $table->date('date_published');
            $table->string('publisher');
            $table->text('copyright');
            $table->integer('volume');
            $table->integer('issue');
            $table->integer('pages');
            $table->text('blurb');
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
        Schema::dropIfExists('periodicals');
    }
};
