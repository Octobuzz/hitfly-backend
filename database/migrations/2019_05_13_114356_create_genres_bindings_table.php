<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenresBindingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('genres_bindings', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('genreable');
            $table->unsignedInteger('genre_id');
            $table->timestamps();
        });

        Schema::table('genres_bindings', function (Blueprint $table) {
            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('albums', function (Blueprint $table) {
            $table->dropColumn('album_img');
            $table->dropForeign('albums_genre_id_foreign');
            $table->dropColumn('genre_id');
            $table->date('year')->change();
            $table->string('cover')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('genres_bindings');

        Schema::table('albums', function (Blueprint $table) {
            $table->string('album_img')->nullable();
            $table->unsignedInteger('genre_id')->nullable();
            $table->integer('year')->change();
            $table->string('cover')->nullable(false)->change();
        });

        Schema::table('albums', function (Blueprint $table) {
            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
