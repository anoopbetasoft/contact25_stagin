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
        'o_delivery_charge',
        'o_delivery_provider',
		'o_quantity',
		'o_purchased_for',
		'o_dispatched',
		'o_dispatched_date',
        'o_parcel_id',
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
        'o_not_delivered',
        'o_not_delivered_date',
        'o_delivered',
        'o_delivered_date',
        'o_subs_period',
        'p_price_per_optn',
        'subs_status',
        'reminder_time'
    ];

    /*
    Relations 
    */
    public function return_history()
    {
        return $this->belongsTo('App\return_history','id','order_id');
    }
    public function item_order_notification()
    {
        return $this->hasMany('App\item_order_notification','id','order_id');
    }
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
    public function tracking_link()
    {
    	return $this->belongsTo('App\deliverie','o_tracking_link','id');
    }
    public function deliveryprovider()
    {
        return $this->belongsTo('App\deliverie','o_delivery_provider','id');
    }
}
