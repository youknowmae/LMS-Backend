<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLockerHistoryTable extends Migration
{
public function up()
{
Schema::create('locker_history', function (Blueprint $table) {
$table->id();
$table->integer('number_of_lockers');
$table->dateTime('added_at');
$table->timestamps();
});
}

public function down()
{
Schema::dropIfExists('locker_history');
}
}
