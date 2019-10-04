<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class P_room extends Model
{
    protected $fillable = [
    	'type',
    	'display_text',
    	'user_id'
    ];
   	public function product()
   	{
   		return $this->hasMany('App\Product','id','room_id');
   	}
   	public function box()
   	{
   		return $this->hasMany('App\p_boxe','id','p_rooms_id');
   	}
}
