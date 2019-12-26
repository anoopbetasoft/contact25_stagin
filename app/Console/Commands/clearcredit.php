<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\system_setting;
use App\Credit_detail;
use App\User;
use App\Order;
//use Log;

class clearcredit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'credit:clearing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron Job To Run Daily For Approaving Credits';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //DB::table('demo')->insert(['name'=>'demoname']);
        //Log::info('cron job runned');
        $date = date('Y-m-d H:i:s');
        $creditperiod = system_setting::where('status','1')->get();
        $creditdetail = Credit_detail::where('status','0')->get();
        foreach($creditdetail as $credits)
        {
            $order = Order::where('id',$credits->order_id)->get();
            $credit_date = date('Y-m-d H:i:s',strtotime($credits->active_on));
            /*if($order[0]['o_product_type']=='1' || $order[0]['o_product_type']=='3')  // if it is product or subscription then add credit period of product or subscription from system setting
            {
                $credit_active_date = date('Y-m-d H:i:s',strtotime($credit_date. '+ '.$creditperiod[0]['clear_credit_period'].' days'));
            }
            if($order[0]['o_product_type']=='2')  // if it is service then add service credit period from system setting
            {
                $credit_active_date = date('Y-m-d H:i:s',strtotime($credit_date. '+ '.$creditperiod[0]['clear_credit_period_service'].' days'));
            }*/
            if(strtotime($credit_date) < strtotime($date))   // If today date is greater than credit active date
            {
                User::where('id',$credits->user_id)->increment('wallet',$credits->value);
                Credit_detail::where('id',$credits->id)->update(['status'=>'1']);
            }
        }
    }
}
