<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class friend extends Model
{
    protected $fillable = [
    	'id',
    	'friend_id_1',
    	'friend_id_2',
    	'status'
    ];

    public function user()
    {
    	return $this->belongsTo('App\User','friend_id_1','id');
    }
    public function user2()
    {
        return $this->belongsTo('App\User','friend_id_2','id');
    }
    public function product()
    {
        return $this->hasMany('App\product','friend_id_1','user_id');
    }
    public function product2()
    {
        return $this->hasMany('App\product2','friend_id_2','user_id');
    }
}
