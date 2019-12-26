<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class paid_detail extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'currency_code',
        'amount',
        'created_at',
        'updated_at'
    ];
    public function currency_symbol()
    {
        return $this->belongsTo('App\currencies','currency_code','code');
    }
}
