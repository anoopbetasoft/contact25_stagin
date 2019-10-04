<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_card extends Model
{
    protected $fillable = [
    	'user_id',
    	'braintree_customer_id',
        //'braintree_transaction_id',
    	'created_at',
    	'updated_at',
    ];

    public function userDet()
    {
        return $this->belongsTo('App\User', 'user_id','id');
    }
}
