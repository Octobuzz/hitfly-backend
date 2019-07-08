<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTrackTableListenTrack extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->integer('count_listen')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn('count_listen');
        });
    }
}
