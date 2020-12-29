<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FavouritesSoftDelete extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('favourites', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('watcheables', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('favourites', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('watcheables', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
