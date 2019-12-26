<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class refund_history extends Model
{
    protected $table = 'refund_history';
    protected $fillable = [
        'id',
        'user_id',
        'order_id',
        'currency_symbol',
        'amount',
        'created_at',
        'updated_at'
    ];
}
