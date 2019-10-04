<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('p_title');
            $table->longText('p_description')->nullable(); 
            $table->unsignedBigInteger('p_quantity')->nullable(); 
            $table->integer('p_quality')->default(1)->comment('1=>like New, 2=>good, 3=>ok'); 
            $table->longText('p_image')->nullable();
            $table->string('p_selling_price')->nullable();
            $table->integer('p_price_per_optn')->default(1)->comment('1=>day, 2=>week, 3=>month, 4=>year')->nullable();
            $table->unsignedInteger('p_type');
            $table->foreign('p_type')->references('id')->on('p_types')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('p_sell_to');
            $table->foreign('p_sell_to')->references('id')->on('p_sell_options')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('p_item_lend_options');
            $table->foreign('p_item_lend_options')->references('id')->on('p_items_options')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('p_lend_price')->nullable();

            $table->unsignedInteger('p_service_option');
            $table->foreign('p_service_option')->references('id')->on('p_service_options')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('p_subs_option');
            $table->foreign('p_subs_option')->references('id')->on('p_subscription_options')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('p_subs_price')->nullable();
            $table->unsignedBigInteger('p_repeat')->nullable();
            $table->integer('p_repeat_forever')->default(0)->comment('0=>no, 1=>yes'); 
            $table->string('p_time')->nullable();
            $table->longText('p_location')->nullable();
            $table->unsignedBigInteger('p_group')->nullable();
            $table->unsignedBigInteger('p_radius')->nullable();
            $table->integer('p_radius_option')->default(0)->comment('0=>nothing selected,1=>miles, 2=>km');
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
        Schema::dropIfExists('products');
    }
}
