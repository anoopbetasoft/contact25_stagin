<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class orders_log extends Model
{
    protected $table = 'orders_log';
    protected $fillable = [
        'id',
        'order_id',
        'order_type',
        'created_at',
        'updated_at'
    ];
}
