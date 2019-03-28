<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('music_group', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creator_group_id');
            $table->string('avatar_group')->nullable();
            $table->string('name', 180);
            $table->dateTime('career_start_year')->default(null);
            $table->integer('type_music_group_id')->nullable();
            $table->integer('genre_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
