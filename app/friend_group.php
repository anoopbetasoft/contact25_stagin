<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class friend_group extends Model
{
    protected $fillable = [
        'user_id',
        'group_name',
        'users',
        'created_at',
        'updated_at'
    ];
}
