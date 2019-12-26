<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vats', function (Blueprint $table) {
            $table->increments('vat_id');
            $table->biginteger('order_id','100');
            $table->float('amount');
            $table->float('fee_gross');
            $table->float('fee_vat');
            $table->float('fee_net');
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
        Schema::dropIfExists('vat');
    }
}
