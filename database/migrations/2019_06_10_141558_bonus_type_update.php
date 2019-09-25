<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BonusTypeUpdate extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('bonus_types', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->text('img')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('bonus_types', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('img');
        });
    }
}
