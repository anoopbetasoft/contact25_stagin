<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit_history extends Model
{
    protected $fillable = [
    		'user_id',
			'order_id',
			'comment',
			'amount',
			'currency',
    ];

    public function userDet()
    {
        return $this->belongsTo('App\User', 'user_id','id');
    }
    public function orderDet()
    {
        return $this->belongsTo('App\Order', 'order_id','id');
    }
    public function currency()
    {
        return $this->belongsTo('App\currencies','currency','code');
    }
}
