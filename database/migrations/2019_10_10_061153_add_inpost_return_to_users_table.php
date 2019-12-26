<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInpostReturnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
           $table->integer('inpost_return','100')->nullable('false')->default('0')->after('sale_cancel_count');
           $table->string('return_address','1000')->nullable('false')->after('inpost_return');
           $table->integer('return_label_status','100')->nullable('false')->default('0')->after('return_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
