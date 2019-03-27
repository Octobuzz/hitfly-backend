<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateForeignKyyMusicGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('music_group', function (Blueprint $table) {
            $table->unsignedInteger('creator_group_id')->nullable(false)->change();
            $table->foreign('creator_group_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('music_group', function (Blueprint $table) {
            $table->dropForeign('music_group_creator_group_id_foreign');
        });
    }
}
