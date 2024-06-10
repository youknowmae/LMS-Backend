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
        Schema::connection('archives')->create('academic_projects', function (Blueprint $table) {
            $table->string('accession')->primary();
            $table->string('category', 50);
            $table->string('title');
            $table->string('authors', 100);
            $table->string('program');
            $table->string('image_url', 100)->nullable();
            $table->date('date_published');
            $table->string('language', 20);
            $table->text('abstract');
            $table->string('keywords', 50);
            
            $table->timestamp('archived_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            // $table->foreign('program')->references('program_short')->on('programs');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archived_academic_projects');
    }
};
