<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtistProfilesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('artist_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unique();
            $table->date('career_start')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('artist_profiles', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('artist_profiles_genre', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('artist_profile_id');
            $table->unsignedInteger('genre_id');
            $table->timestamps();
        });

        Schema::table('artist_profiles_genre', function (Blueprint $table) {
            $table->foreign('artist_profile_id')->references('id')->on('artist_profiles')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('music_group', function (Blueprint $table) {
            $table->unsignedInteger('city_id')->change();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('city_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('artist_profiles_genre');
        Schema::dropIfExists('artist_profiles');
        Schema::table('users', function (Blueprint $table) {
            $table->string('city_id')->change();
        });
        Schema::table('music_group', function (Blueprint $table) {
            $table->string('city_id')->change();
        });
    }
}
