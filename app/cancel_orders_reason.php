<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cancel_orders_reason extends Model
{
   protected $fillable = [
        'reason_id',
        'user_id',
        'order_id',
        'reason'
    ];
}
