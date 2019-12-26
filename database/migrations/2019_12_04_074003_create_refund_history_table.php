<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_history', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id','250')->after('id');
            $table->string('order_id','250')->after('user_id');
            $table->string('currency_symbol','250')->after('order_id');
            $table->string('amount','250')->after('currency_symbol');
            $table->string('created_at','250')->after('amount');
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
        Schema::dropIfExists('refund_history');
    }
}
