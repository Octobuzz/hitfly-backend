<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TrackTableUpdate extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->string('singer')->nullable(true)->change();
            $table->string('track_date')->nullable(true)->change();
            $table->string('song_text')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->string('singer')->nullable(false)->change();
            $table->string('track_date')->nullable(false)->change();
            $table->string('song_text')->nullable(false)->change();
        });
    }
}
