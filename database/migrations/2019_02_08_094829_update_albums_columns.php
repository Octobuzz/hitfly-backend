<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAlbumsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('albums', function (Blueprint $table){
            $table->unsignedInteger('music_group_id')->nullable();
            //todo remove foreign, music_group is softdeleted
            $table->foreign('music_group_id')->references('id')->on('music_group')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('likes')->nullable()->change();
            $table->integer('dislikes')->nullable()->change();
            $table->softDeletes();
        });

        Schema::table('music_group', function (Blueprint $table){
            $table->unsignedInteger('genre_id')->nullable()->change();
            //todo remove foreign, genre_id is softdeleted
            $table->foreign('genre_id')->references('id')->on('genres');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::table('albums', function (Blueprint $table){
            $table->dropForeign('albums_music_group_id_foreign');
            $table->removeColumn('music_group_id');
        });
        Schema::table('music_group', function (Blueprint $table){
            $table->dropForeign('music_group_genre_id_foreign');
        });
        Schema::connection($connection)->dropIfExists('music_group');


    }
}
