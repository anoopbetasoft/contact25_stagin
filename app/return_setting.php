<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class return_setting extends Model
{
    protected $fillable = [
        'id',
        'days_allowed_for_refund',
        'credit_limit_refund',
        'inpost_return_amount',
        'days_allowed_for_return_label',
        'status',
        'product_returning_limit',
        'created_at'
    ];
}
