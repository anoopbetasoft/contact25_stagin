<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit_detail extends Model
{
    protected $fillable = [
    		'user_id',
			'order_id',
			'value',
			'cd_commission',
			'cd_commission_vat',
			'cd_paypal_email',
			'added_on',
    ];

    public function userDet()
    {
        return $this->belongsTo('App\User', 'user_id','id');
    }
    public function orderDet()
    {
        return $this->belongsTo('App\Order', 'order_id','id');
    }
}
