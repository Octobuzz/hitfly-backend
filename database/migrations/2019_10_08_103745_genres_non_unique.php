<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GenresNonUnique extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('genres', function (Blueprint $table) {
            $table->dropUnique('genres_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('genres', function (Blueprint $table) {
            $table->string('name', 150)->unique()->change();
        });
    }
}
