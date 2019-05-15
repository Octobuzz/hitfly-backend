<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CoverTracks extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->string('cover')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->removeColumn('cover');
        });
    }
}
