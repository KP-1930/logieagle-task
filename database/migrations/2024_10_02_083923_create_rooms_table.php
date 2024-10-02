<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_no')->unique();
            $table->enum('room_type', ['Deluxe', 'Luxury', 'Royal']);
            $table->boolean('is_bathtub')->comment('1 => Yes, 0 => No')->default(0);
            $table->boolean('is_balcony')->comment('1 => Yes, 0 => No')->default(0);
            $table->boolean('is_mini_bar')->comment('1 => Yes, 0 => No')->default(0);
            $table->integer('max_occupancy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
