<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->string('track_name', 180)->nullable(false);
            $table->integer('album_id')->unsigned()->nullable(true);
            $table->integer('genre_id')->unsigned()->nullable(true);
            $table->string('singer', 180)->nullable(false);
            $table->dateTime('track_date')->nullable(false);
            $table->text('song_text')->nullable(false);
            $table->string('track_hash')->nullable(false);
            $table->string('filename')->nullable(false);
            $table->string('state')->nullable(false);
            $table->integer('user_id')->unsigned()->nullable(false);
            $table->softDeletes();
        });

        Schema::table('tracks', function (Blueprint $table) {
            $table->foreign('genre_id', 'foreign_tracks_genres')->references('id')->on('genres');
            $table->foreign('album_id', 'foreign_tracks_albums')->references('id')->on('albums');
            $table->foreign('user_id', 'foreign_tracks_users')->references('id')->on('users');
            $table->index('track_hash', 'index_track_hash');
            $table->index('track_name', 'index_track_name');
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
            $table->dropSoftDeletes();
            $table->dropForeign('foreign_tracks_genres');
            $table->dropForeign('foreign_tracks_albums');
            $table->dropForeign('foreign_tracks_users');
            $table->dropIndex('index_track_hash');
        });

        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn('track_name');
            $table->dropColumn('album_id');
            $table->dropColumn('genre_id');
            $table->dropColumn('singer');
            $table->dropColumn('track_date');
            $table->dropColumn('song_text');
            $table->dropColumn('user_id');
        });


    }
}
