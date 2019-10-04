<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    //
     protected $fillable = [
    	'user_id',
    	'start',
    	'end',
    	'created_at',
    	'updated_at'
    ];
}
