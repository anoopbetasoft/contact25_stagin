<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaidDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paid_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id','250')->after('id');
            $table->string('currency_code','250')->after('user_id');
            $table->string('amount','250')->after('currency_code');
            $table->integer('created_at','250')->after('amount');
            $table->integer('updated_at','250')->after('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paid_details');
    }
}
