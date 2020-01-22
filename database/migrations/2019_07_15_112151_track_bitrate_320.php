<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TrackBitrate320 extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->string('bitrate_hight')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->removeColumn('bitrate_hight');
        });
    }
}
