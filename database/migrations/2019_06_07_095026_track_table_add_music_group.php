<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TrackTableAddMusicGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->integer('music_group_id')->unsigned()->nullable(true);
        });
        Schema::table('tracks', function (Blueprint $table) {
            $table->foreign('music_group_id', 'foreign_tracks_music_group')->references('id')->on('music_group');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropForeign('foreign_tracks_music_group');
        });
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn('music_group_id');
        });
    }
}
