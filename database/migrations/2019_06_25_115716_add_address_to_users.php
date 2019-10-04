<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('street_address1')->nullable()->after('contact_verify_status');
            $table->string('street_address2')->nullable()->after('contact_verify_status');
            $table->string('city')->nullable()->after('contact_verify_status');
            $table->string('state')->nullable()->after('contact_verify_status');
            $table->string('country')->nullable()->after('contact_verify_status');
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
