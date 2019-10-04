<?php
 
namespace App\Traits;
use Config;
use Auth;
use Illuminate\Foundation\Auth\User;
use carbon;
 
trait TimezoneTrait {
 
    public function verifytimezone($timezone) {
 		if(isset($timezone) && $timezone!='')
 		{
 			date_default_timezone_set($timezone);
 		}
 		else
 		{
 			
 		}
    }
 
}