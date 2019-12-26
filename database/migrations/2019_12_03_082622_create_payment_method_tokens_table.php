<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_method_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id','250')->after('id');
            $table->string('payment_method_token','250')->after('order_id');
            $table->string('customer_id','250')->after('payment_method_token');
            $table->string('created_at','250')->after('customer_id');
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
        Schema::dropIfExists('payment_method_tokens');
    }
}
