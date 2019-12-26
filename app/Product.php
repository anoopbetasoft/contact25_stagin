<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{   
    use HasSlug;

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('p_title')
            ->saveSlugsTo('p_slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
    protected $fillable = [

    	'user_id',
        'room_id',
		'p_title',
		'p_description',
		'p_quantity',
		'p_quality',
		'p_image',
        'code',
		'p_selling_price',
		'p_price_per_optn',
		'p_type',
		'p_sell_to',
		'p_item_lend_options',
		'p_lend_price',
		'p_service_option',
		'p_subs_option',
		'p_subs_price',
		'p_repeat',
		'p_repeat_forever',
		'p_time',
		'p_location',
		'p_group',
		'p_radius',
		'p_radius_option',
        'p_repeat_per_option',
        'p_box',
        'p_slug',
        'service_lead_time',
        'service_time',
        'service_time_type',
        'p_delivery_option',
        'price',
	];

	public function currency()
    {
        return $this->belongsTo('App\currencies','code','code');
    }
    public function userDet()
    {
        return $this->belongsTo('App\User', 'user_id','id');
    }
    public function product_type()
    {
        return $this->belongsTo('App\P_type', 'p_type','id');
    }
    public function product_sell_options()
    {
        return $this->belongsTo('App\P_sell_option', 'p_sell_to','id');
    }
    public function product_lend_options()
    {
        return $this->belongsTo('App\P_items_option', 'p_item_lend_options','id');
    }
    public function product_service_option()
    {
        return $this->belongsTo('App\P_service_option', 'p_service_option','id');
    }
    public function product_subs_option()
    {
        return $this->belongsTo('App\P_subscription_option', 'p_subs_option','id');
    }
    public function product_room()
    {
        return $this->belongsTo('App\P_room', 'room_id','id');
    }
    public function stopwords()
    {
        return $this->belongsTo('App\search_stop_word');
    }
    public function box()
    {
        return $this->belongsTo('App\p_boxe','p_box','id');
    }
    public function friend()
    {
        return $this->belongsTo('App\friend','user_id','friend_id_1');
    }
    public function friend2()
    {
        return $this->belongsTo('App\friend','user_id','friend_id_2');
    }

}

