<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vat extends Model
{
   protected $fillable = [
    	'vat_id',
    	'order_id',
    	'amount',
    	'fee_gross',
    	'fee_vat',
    	'fee_net',
    	'created_at',
    	'updated_at',
    ];
}
