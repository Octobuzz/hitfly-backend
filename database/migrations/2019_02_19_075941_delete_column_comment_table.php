<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnCommentTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('comment_date');
            $table->dropForeign('foreign_comments_tracks');
            $table->dropForeign('foreign_comments_albums');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->renameColumn('track_id', 'commentable_id');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->renameColumn('album_id', 'commentable_type');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->string('commentable_type', 40)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dateTime('comment_date')->nullable(false)->default('');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->renameColumn('commentable_id', 'track_id');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->renameColumn('commentable_type', 'album_id');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('track_id', 'foreign_comments_tracks')->references('id')->on('tracks');
            $table->foreign('album_id', 'foreign_comments_albums')->references('id')->on('albums');
            $table->integer('album_id')->unsigned()->nullable(true)->change();
        });
    }
}
