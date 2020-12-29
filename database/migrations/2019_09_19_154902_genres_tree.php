<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GenresTree extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('genres', function (Blueprint $table) {
            $table->nestedSet();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('genres', function (Blueprint $table) {
            $table->dropNestedSet();
        });
    }
}
