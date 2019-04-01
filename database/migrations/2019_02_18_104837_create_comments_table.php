<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('track_id')->unsigned()->nullable(true); //todo morphable
            $table->integer('album_id')->unsigned()->nullable(true); //todo morphable
            $table->integer('user_id')->unsigned()->nullable(true);
            $table->text('comment')->nullable(false);
            $table->dateTime('comment_date')->nullable(false); //todo remove
            $table->tinyInteger('estimation')->unsigned()->nullable(true);
            $table->timestamps();
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('track_id', 'foreign_comments_tracks')->references('id')->on('tracks');
            $table->foreign('album_id', 'foreign_comments_albums')->references('id')->on('albums');
            $table->foreign('user_id', 'foreign_comments_users')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
