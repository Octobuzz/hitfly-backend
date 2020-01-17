<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTracksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->string('track_name', 180)->nullable();
            $table->integer('album_id')->unsigned()->nullable(true);
            $table->integer('genre_id')->unsigned()->nullable(true);
            $table->string('singer', 180)->nullable();
            $table->dateTime('track_date')->nullable(); //todo remove
            $table->text('song_text')->nullable();
            $table->string('track_hash')->nullable();
            $table->string('filename')->nullable();
            $table->string('state')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->softDeletes(); //todo remove
        });
        //добавил костыль с nullable из-за тестов и особеностей sqlite. SQLite General error: 1 Cannot add a NOT NULL column with default value NULL
        Schema::table('tracks', function (Blueprint $table) {
            $table->string('track_name', 180)->nullable(false)->change();
            $table->string('singer', 180)->nullable(false)->change();
            $table->dateTime('track_date')->nullable(false)->change();
            $table->text('song_text')->nullable(false)->change();
            $table->string('track_hash')->nullable(false)->change();
            $table->string('filename')->nullable(false)->change();
            $table->string('state')->nullable(false)->change();
            $table->integer('user_id')->nullable(false)->change();
        });

        Schema::table('tracks', function (Blueprint $table) {
            $table->foreign('genre_id', 'foreign_tracks_genres')->references('id')->on('genres'); //todo remove foreign
            $table->foreign('album_id', 'foreign_tracks_albums')->references('id')->on('albums');
            $table->foreign('user_id', 'foreign_tracks_users')->references('id')->on('users');
            $table->index('track_hash', 'index_track_hash');
            $table->index('track_name', 'index_track_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign('foreign_tracks_genres');
            $table->dropForeign('foreign_tracks_albums');
            $table->dropForeign('foreign_tracks_users');
            $table->dropIndex('index_track_hash');
        });

        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn('track_name');
        });

        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn('album_id');

        });

        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn('genre_id');
        });

        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn('singer');
        });

        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn('track_date');
        });

        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn('song_text');
        });

        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
