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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->enum('material_type', ['journal', 'magazine', 'newspaper']);
            $table->string('title');
            $table->string('authors');
            $table->string('language');
            $table->string('subject');
            $table->date('date_published');
            $table->string('publisher');
            $table->integer('volume')->nullable();
            $table->integer('issue')->nullable();
            $table->string('pages');
            $table->text('abstract')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('articles');
    }
};
