<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreditdiscounttoSystemSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
           $table->string('credit_discount','250')->after('clear_credit_period');
           $table->string('clear_credit_period_service','250')->after('credit_discount');
           $table->string('status','250')->after('clear_credit_period_service');
           $table->integer('remind_time','250')->default('0')->after('status');
           $table->string('created_at','250')->after('credit_discount');
           $table->string('updated_at','250')->after('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
