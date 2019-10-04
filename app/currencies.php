<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class currencies extends Model
{
    protected $fillable = [

    	'name',
        'code',
		'symbol',
	];
	public function product()
	{
		return $this->hasMany('App\Product','code','code');
	}
	public function order()
	{
		return $this->hasMany('App\Order','code','o_currency');
	}

}
