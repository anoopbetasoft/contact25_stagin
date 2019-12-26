<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('days_allowed_for_refund','250')->comment('no of days allowed for refund');
            $table->string('credit_limit_refund','250')->comment('credit limit for refund label');
            $table->string('inpost_return_amount','250')->comment('inpost return amount');
            $table->string('days_allowed_for_return_label','250')->comment('no of days allowed for return label');
            $table->string('status','250');
            $table->string('created_at','250');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('return_settings');
    }
}
