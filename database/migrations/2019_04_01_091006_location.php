<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Location extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('title');
            $table->string('area_region')->nullable();
            $table->decimal('lat')->nullable();
            $table->decimal('long')->nullable();
            $table->string('lower_corner', 30)->nullable();
            $table->string('upper_corner', 30)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['title', 'area_region'])->unique();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('city_id')->nullable();
        });

        Schema::table('music_group', function (Blueprint $table) {
            $table->string('city_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('cities');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('city_id');
        });

        Schema::table('music_group', function (Blueprint $table) {
            $table->dropColumn('city_id');
        });
    }
}
