<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class merchant_detail extends Model
{
    protected $fillable = [
       'id',
        'user_id',
        'firstName',
        'lastName',
        'email',
        'phone',
        'dateOfBirth',
        'ssn',
        'streetAddress',
        'locality',
        'region',
        'postalCode',
        'legalName',
        'dbaName',
        'taxId',
        'streetAddress2',
        'locality2',
        'region2',
        'postalCode2',
        'destination',
        'email2',
        'mobilePhone',
        'accountNumber',
        'routingNumber',
        'created_at',
        'updated_at',
    ];
}
