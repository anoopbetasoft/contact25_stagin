<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class payment_method_token extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'payment_method_token',
        'customer_id',
        'created_at',
        'updated_at'
    ];
}
