<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->unsignedBigInteger('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->dateTime('start_date');
            $table->dateTime('end_date'); // Date when the reservation expires
            $table->float('fine')->nullable(); // Fine amount, nullable as it might not always apply
            $table->boolean('status')->default(true); // True for active reservation, False for completed/canceled
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
