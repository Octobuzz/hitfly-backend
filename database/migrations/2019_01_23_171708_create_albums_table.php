<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->string('title');
            $table->string('author');
            $table->integer('year');
            $table->string('cover');
            $table->integer('likes');
            $table->integer('dislikes');
            $table->timestamps();

            $table->unsignedInteger('genre_id');
            //todo remove foreign, genre_id is softdeleted
            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('albums');
    }
}
