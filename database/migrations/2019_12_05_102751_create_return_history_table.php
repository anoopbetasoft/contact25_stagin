<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_history', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id','250')->after('id');
            $table->integer('return_type','10')->after('order_id');
            $table->string('return_reason','2000')->after('return_type');
            $table->string('return_label','2000')->default('0')->after('return_reason');
            $table->integer('return_status','200')->after('return_label');
            $table->string('created_at','250')->after('return_status');
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
        Schema::dropIfExists('return_history');
    }
}
