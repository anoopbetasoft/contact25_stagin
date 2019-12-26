<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class deliverie extends Model
{
    protected $fillable = [

    	'id',
        'user_id',
		'delivery_provider',
		'tracking_url',
		'description',
		'price',
		'updated_at',
		'created_at'
	];
	/* Relations */
	public function order_tracking_link()
	{
		return $this->hasMany('App\Order','id','o_tracking_link');
	}
	public function orders()
	{
		return $this->hasMany('App\Order','id','o_delivery_provider');
	}
}
