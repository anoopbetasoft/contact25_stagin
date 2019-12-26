<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class return_history extends Model
{
    protected $table = 'return_history';
    protected $fillable = [
        'id',
        'buyer_id',
        'order_id',
        'return_type',
        'return_reason',
        'return_label',
        'return_address',
        'return_status'
    ];
    public function order()
    {
        return $this->belongsTo('App\Order','order_id','id');
    }
}
