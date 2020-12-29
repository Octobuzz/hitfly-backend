<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableUsersSoftDelete extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('foreign_comments_users');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('user_id', 'foreign_comments_users')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropForeign('foreign_tracks_users');
            $table->dropForeign('foreign_tracks_albums');
        });
        Schema::table('tracks', function (Blueprint $table) {
            $table->foreign('user_id', 'foreign_tracks_users')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('album_id', 'foreign_tracks_albums')->references('id')->on('albums')->onDelete('cascade');
        });
        Schema::table('music_group', function (Blueprint $table) {
            $table->dropForeign('music_group_creator_group_id_foreign');
        });
        Schema::table('music_group', function (Blueprint $table) {
            $table->foreign('creator_group_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('foreign_comments_users');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('user_id', 'foreign_comments_users')->references('id')->on('users');
        });
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropForeign('foreign_tracks_users');
            $table->dropForeign('foreign_tracks_albums');
        });
        Schema::table('tracks', function (Blueprint $table) {
            $table->foreign('user_id', 'foreign_tracks_users')->references('id')->on('users');
            $table->foreign('album_id', 'foreign_tracks_albums')->references('id')->on('albums');
        });
        Schema::table('music_group', function (Blueprint $table) {
            $table->dropForeign('music_group_creator_group_id_foreign');
        });
        Schema::table('music_group', function (Blueprint $table) {
            $table->foreign('creator_group_id')->references('id')->on('users');
        });
    }
}
