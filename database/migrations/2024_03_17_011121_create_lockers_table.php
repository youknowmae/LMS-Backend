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
        Schema::create('lockers', function (Blueprint $table) {


            $table->id();
            $table->string('lockerNumber')->unique();
            $table->string('studentNumber')->unique()->nullable(); // Set to nullable
            $table->string('collegeProgram')->nullable();
            $table->string('collegeDepartment')->nullable();
            $table->foreignId('user_id')->unique()->nullable()->references('id')->on('users');
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->enum('status', ['Occupied', 'Available', 'Unavailable'])->default('Available');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lockers');
    }
};
