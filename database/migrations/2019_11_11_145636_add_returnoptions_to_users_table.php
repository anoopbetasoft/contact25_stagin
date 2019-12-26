<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReturnoptionsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('refundrequest_status','250')->default('0')->after('delivery_option');
            $table->string('refundrequest_value','250')->default('0')->after('refundrequest_status');
            $table->string('refundrequestdamage_status','250')->default('0')->after('refundrequest_value');
            $table->string('refundrequestdamage_value','250')->default('0')->after('refundrequestdamage_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
