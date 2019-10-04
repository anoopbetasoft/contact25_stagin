<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class p_boxe extends Model
{
    //
    protected $fillable = [
    	'p_rooms_id',
    	'box_name',
      'status',
      'created_at',
      'updated_at'
    ];
   	public function product()
   	{
   		return $this->hasMany('App\Product','id','p_box');
   	}
   	public function rooms()
   	{
   		return $this->belongsTo('App\P_room','p_rooms_id','id');
   	}
    public function products()
    {
      return $this->hasManyThrough('App\P_room', 'App\Product');
    }

}
