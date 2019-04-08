<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInviteToGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invite_to_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('music_group_id');
            $table->foreign('music_group_id')->references('id')->on('music_group')->onDelete('cascade')->onUpdate('cascade');
            $table->string('email')->nullable(true);
            $table->unsignedInteger('user_id')->nullable(true);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('accept')->default(false)->nullable(true);;
            $table->softDeletes();
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
        Schema::dropIfExists('invite_to_groups');
    }
}
