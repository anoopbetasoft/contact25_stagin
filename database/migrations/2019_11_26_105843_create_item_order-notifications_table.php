<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemOrderNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_order_notifications', function (Blueprint $table) {
            $table->bigIncrements('notification_id','250');
            $table->bigInteger('order_id','250');
            $table->bigInteger('user_id','250');
            $table->string('link','250');
            $table->integer('status','250');
            $table->string('created_at','250');
            $table->string('updated_at','250');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_order_notifications');
    }
}
