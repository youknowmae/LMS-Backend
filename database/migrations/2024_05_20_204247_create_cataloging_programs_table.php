<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogingProgramsTable extends Migration
{
    public function up()
    {
        Schema::create('cataloging_programs', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('program')->unique(); // Unique Key
            $table->unsignedBigInteger('department_id'); // Foreign Key
            $table->string('category');
            $table->timestamps();

            // Setting up the foreign key constraint
            $table->foreign('department_id')
                ->references('id')
                ->on('cataloging_departments')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cataloging_programs');
    }
}
