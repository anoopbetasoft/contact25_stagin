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
		'price',
		'updated_at',
		'created_at'
	];
}
