<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BonusTypesShowUser extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('bonus_types', function (Blueprint $table) {
            $table->boolean('show_user')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('bonus_types', function (Blueprint $table) {
            $table->dropColumn('show_user');
        });
    }
}
