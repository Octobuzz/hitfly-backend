<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteSinger extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn('singer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->string('singer')->nullable(true)->default('');
        });
    }
}
