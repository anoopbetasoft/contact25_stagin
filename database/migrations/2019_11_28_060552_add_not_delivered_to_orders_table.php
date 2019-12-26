<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotDeliveredToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
           $table->integer('o_not_delivered','10')->default('0')->after('o_completed_on');
           $table->string('o_not_delivered_date','250')->default('0')->after('o_not_delivered');
           $table->integer('o_delivered','10')->default('0')->after('o_not_delivered_date');
           $table->string('o_delivered_date','250')->default('0')->after('o_delivered');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
