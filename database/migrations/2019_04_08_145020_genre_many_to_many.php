<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GenreManyToMany extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('music_group', function (Blueprint $table) {
            $table->dropForeign('music_group_genre_id_foreign');
            $table->dropColumn('genre_id');
        });
        Schema::create('music_group_genre', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('music_group_id');
            $table->foreign('music_group_id')->references('id')->on('music_group')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('genre_id');
            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('music_group', function (Blueprint $table) {
            $table->unsignedInteger('genre_id')->nullable();
            $table->foreign('genre_id')->references('id')->on('genres');
        });

        Schema::dropIfExists('music_group_genre');
    }
}
