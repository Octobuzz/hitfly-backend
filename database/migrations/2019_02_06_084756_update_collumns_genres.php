<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCollumnsGenres extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        //добавил костыль с nullable из-за тестов и особеностей sqlite. SQLite General error: 1 Cannot add a NOT NULL column with default value NULL
        Schema::table('genres', function (Blueprint $table) {
            $table->string('name', 30)->unique()->nullable();
            $table->string('image')->nullable();
        });

        Schema::table('genres', function (Blueprint $table) {
            $table->string('name', 30)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('genres', function (Blueprint $table) {
            $table->removeColumn('name');
            $table->removeColumn('image');
        });
    }
}
