<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLifehack extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('lifehacks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image');
            $table->string('title');
            $table->integer('sort')->default(500);
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('lifehack_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lifehack_id')->unsigned();
            $table->integer('tag_id')->unsigned();
        });

        Schema::table('lifehack_tag', function (Blueprint $table) {
            $table->foreign('lifehack_id')->references('id')->on('lifehacks')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('artist_profiles_genre');
        Schema::dropIfExists('artist_profiles');
        Schema::dropIfExists('lifehacks');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('lifehack_tag');
    }
}
