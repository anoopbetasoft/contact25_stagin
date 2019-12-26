<?php
 
namespace App\Traits;
use Config;
use Auth;
use Illuminate\Foundation\Auth\User;
use carbon;
 
trait TimezoneTrait {
 
    public function verifytimezone($timezone)
    {
        if(isset($timezone) && $timezone!='')
        {
            date_default_timezone_set($timezone);
        }
        else
        {

        }
    }
    public function summertime()
    {
    	if(date('I',time()))
		{
			/*echo 'We are in DST!';
			die;*/
			return date('Y-m-d H:i:s',strtotime('+1 hours'));
		}
		else
		{
			/*echo 'We are not in DST!';
			die;*/
			return date('Y-m-d H:i:s');
		}
    }
 
}