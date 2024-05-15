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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->string('title'); // Title of the reservation
            $table->string('author'); // Author of the reserved book
            $table->string('location'); // Location where the book is reserved
            $table->dateTime('date_requested'); // Date when the reservation was requested
            $table->integer('number_of_books'); // Number of books reserved
            $table->dateTime('date_of_expiration'); // Date when the reservation expires
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
