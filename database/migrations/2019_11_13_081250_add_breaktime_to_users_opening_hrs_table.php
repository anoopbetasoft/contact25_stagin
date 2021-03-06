<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBreaktimeToUsersOpeningHrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_opening_hrs', function (Blueprint $table) {
            $table->integer('break_time',250)->default('0')->after('user_end_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_opening_hrs', function (Blueprint $table) {
            //
        });
    }
}
