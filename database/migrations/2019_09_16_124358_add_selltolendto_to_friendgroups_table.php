<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSelltolendtoToFriendgroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('friend_groups', function (Blueprint $table) {
            $table->integer('sell_to')->after('users');
            $table->integer('lend_to')->after('sell_to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('friend_groups', function (Blueprint $table) {
            //
        });
    }
}
