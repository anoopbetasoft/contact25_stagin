<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
    	'order_id',
		'seller_id',
		'user_id',
		'braintree_id',
		'o_name',
		'o_email',
		'o_address',
		'o_postal_code',
		'o_country',
		'o_product_id',
		'o_product_type',
		'o_shipping_service',
		'o_currency',
		'o_sub_total',
		'o_total',
		'o_quantity',
		'o_purchased_for',
		'o_dispatched',
		'o_dispatched_date',
		'o_returned',
		'o_returned_date',
		'o_cancelled',
		'o_cancelled_date',
		'o_tracking_no',
		'o_tracking_link',
		'o_collection_time',
		'o_lend_subscribe_starts',
		'o_lend_subscribe_ends',
		'o_completed_on',
		'o_completed',
    ];

    /*
    Relations 
    */
    public function currency()
    {
    	return $this->belongsTo('App\currencies','o_currency','code');
    }
    public function sellerDetails()
    {
        return $this->belongsTo('App\User', 'seller_id','id');
    }
    public function userDetails()
    {
        return $this->belongsTo('App\User', 'user_id','id');
    }
    public function product_type()
    {
        return $this->belongsTo('App\P_type', 'o_product_type','id');
    }
    public function product_details()
    {
        return $this->belongsTo('App\Product', 'o_product_id','id');
    }
}
