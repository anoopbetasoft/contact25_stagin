<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class system_setting extends Model
{
    protected $fillable = [
        'id',
        'inpost_amount',
        'clear_credit_period',
        'credit_discount',
        'clear_credit_period_service',
        'status',
        'remind_time',
        'product_not_delivered_limit',
        'product_cancel_limit_seller',
        'no_of_day_for_claim',
    ];
}
