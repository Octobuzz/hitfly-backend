<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_links', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('music_group_id');
            $table->foreign('music_group_id')->references('id')->on('music_group')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('social_type',['facebook','instagram','vkontakte','odnoklassniki'])->nullable(false);
            $table->string('link')->nullable(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_links');
    }
}
