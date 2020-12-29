<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BonusProgram extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('purse', function (Blueprint $table) {
            $table->increments('id');
            $table->float('balance');
            $table->unsignedInteger('user_id');
            $table->text('name');
            $table->foreign('user_id', 'foreign_operation_users')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('bonus_types', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->text('constant_name');
            $table->unsignedInteger('bonus');
        });

        Schema::create('operation', function (Blueprint $table) {
            $table->increments('id');
            $table->text('direction');
            $table->float('amount');
            $table->text('description');
            $table->json('extra_data')->nullable();
            $table->unsignedInteger('type_id')->nullable();
            $table->unsignedInteger('purse_id');
            $table->timestamps();
            $table->foreign('purse_id', 'foreign_operation_purse')->references('id')->on('purse')->onDelete('cascade');
            $table->foreign('type_id', 'foreign_operation_type')->references('id')->on('bonus_types')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->date('last_entrance_app')->nullable();
            $table->date('count_entrance_app')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('operation');
        Schema::dropIfExists('bonus_types');
        Schema::dropIfExists('purse');
    }
}
