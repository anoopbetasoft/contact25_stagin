<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommunicationToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
           $table->string('order_status')->after('lend_to_uk')->comment('0=> dont receive 1=> Receive')->default(0);
           $table->string('message_status')->after('lend_to_uk')->comment('0=> dont receive 1=> receive')->default(0);
           $table->string('collect_status')->after('lend_to_uk')->comment('0=> dont remind before collect 1=> remind before collect')->default(0);
           $table->string('collection_status')->after('lend_to_uk')->comment('0=> dont remind before collection 1=> remind before collection')->default(0);

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
