<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            //users, product types, 
            $table->increments('id');

            $table->unsignedInteger('seller_id');
            $table->foreign('seller_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('braintree_id');
            $table->string('o_name');
            $table->string('o_email');
            $table->string('o_address');
            $table->string('o_postal_code');
            $table->string('o_country');
            
            $table->integer('o_product_id');
            $table->unsignedInteger('o_product_type');
            $table->foreign('o_product_type')->references('id')->on('p_types')->onUpdate('cascade')->onDelete('cascade');
            $table->string('o_shipping_service')->nullable();
            $table->string('o_currency')->default('GBP');
            $table->string('o_sub_total');
            $table->string('o_total');
            $table->string('o_quantity')->nullable();
            $table->string('o_price');
            $table->string('o_purchased_for');
            $table->integer('o_dispatched')->default('0');
            $table->string('o_dispatched_date')->nullable();
            $table->integer('o_returned')->default('0');
            $table->string('o_returned_date')->nullable();
            $table->integer('o_cancelled')->default('0');
            $table->string('o_cancelled_date')->nullable();
            $table->string('o_tracking_no')->nullable();
            $table->string('o_tracking_link')->nullable();
            $table->string('o_collection_time')->nullable();
            $table->string('o_lend_subscribe_starts')->nullable();
            $table->string('o_lend_subscribe_ends')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
