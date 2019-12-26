<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class item_order_notification extends Model
{
    protected $fillable = [
       'notification_id',
        'order_id',
        'user_id',
        'link',
        'status',
        'type',
        'created_at',
        'updated_at'
    ];
    /*
   Relations
   */
    public function Order()
    {
        return $this->belongsTo('App\Order','order_id','id');
    }
}
