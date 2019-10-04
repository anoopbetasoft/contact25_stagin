<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableToColumnsProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('p_type')->nullable()->change();
            $table->unsignedInteger('p_sell_to')->nullable()->change();
            $table->unsignedInteger('p_item_lend_options')->nullable()->change();
            $table->unsignedInteger('p_service_option')->nullable()->change();
            $table->unsignedInteger('p_subs_option')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('columns_products', function (Blueprint $table) {
            //
        });
    }
}
