<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogingDepartmentsTable extends Migration
{
    public function up()
    {
        Schema::create('cataloging_departments', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('department')->unique(); // Unique Key
            $table->string('full_department');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cataloging_departments');
    }
}
