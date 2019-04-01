<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Watching extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('watcheables', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('watcheable'); // для сущностей user, musicGroup
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('watcheables');
    }
}
