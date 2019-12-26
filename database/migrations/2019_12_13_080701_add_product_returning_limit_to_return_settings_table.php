<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductReturningLimitToReturnSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('return_settings', function (Blueprint $table) {
           $table->integer('product_returning_limit',10)->default('0')->after('days_allowed_for_return_label');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('return_settings', function (Blueprint $table) {
            //
        });
    }
}
