<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class service_opening_hr extends Model
{
    protected $fillable = [
        'user_id',
        'user_day',
        'user_day_name',
        'user_start_time',
        'user_end_time',
        'created_at',
        'updated_at'
    ];
}
