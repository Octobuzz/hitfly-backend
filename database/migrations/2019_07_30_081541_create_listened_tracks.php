<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListenedTracks extends Migration
{
    /**
     * Таблица прослушаных пользователем треков(для сбора статистики).
     */
    public function up()
    {
        Schema::create('listened_tracks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('user_id')->unsigned();
            $table->integer('track_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('listened_tracks', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('track_id')->references('id')->on('tracks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('listened_tracks');
    }
}
