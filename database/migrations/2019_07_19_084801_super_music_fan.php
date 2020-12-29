<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SuperMusicFan extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->boolean('super_music_fan')->nullable()->default(false);
            $table->boolean('is_admin')->nullable()->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->removeColumn('super_music_fan');
        });
    }
}
