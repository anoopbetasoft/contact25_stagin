<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id','250')->after('id');
            $table->string('firstName','250')->after('user_id');
            $table->string('lastName','250')->after('firstName');
            $table->string('email','250')->after('lastName');
            $table->string('phone','250')->nullable()->after('email');
            $table->string('dateOfBirth','250')->after('phone');
            $table->string('ssn','250')->nullable()->after('dateOfBirth');
            $table->string('streetAddress','250')->after('ssn');
            $table->string('locality','250')->after('streetAddress');
            $table->string('region','250')->after('locality');
            $table->string('postalCode','250')->after('region');
            $table->string('legalName','250')->nullable()->after('postalCode');
            $table->string('dbaName','250')->nullable()->after('legalName');
            $table->string('taxId','250')->nullable()->after('dbaName');
            $table->string('streetAddress2','250')->nullable()->after('taxId');
            $table->string('locality2','250')->nullable()->after('streetAddress2');
            $table->string('region2','250')->nullable()->after('locality2');
            $table->string('postalCode2','250')->nullable()->after('region2');
            $table->string('destination','250')->after('postalCode2');
            $table->string('email2','250')->nullable()->after('destination');
            $table->string('mobilePhone','250')->nullable()->after('email2');
            $table->string('accountNumber','250')->after('mobilePhone');
            $table->string('routingNumber','250')->after('accountNumber');
            $table->string('created_at','250')->after('routingNumber');
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
        Schema::dropIfExists('merchant_details');
    }
}
