<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order;
use App\item_order_notification;
use App\User;
use Log;
class ReminderCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron Job for reminding collection before 10 min of collection';

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
        //Log::debug('Reminder cron job run');
        $orders = Order::where('o_completed','0')->where('o_collection_time','!=','')->orderBy('id','ASC')->get(); // get order with complete status 0 in ascending order of order id
        foreach ($orders as $orderkey => $order)
        {
            $multiple_collect_time = explode(',',$order['o_collection_time']);
            $user = User::where('id',$order['seller_id'])->get();
            if(count($multiple_collect_time)>'1')   // If it is multiple slots
            {

                for($i=0;$i<count($multiple_collect_time);$i++)
                {
                    $collect_time = explode('-',$multiple_collect_time[$i]);
                    date_default_timezone_set($user[0]['timezone']);
                    $reminder_time = date("Y-m-d H:i", strtotime("+".$order['reminder_time']." minutes")); // Adding Remind Time Minutes to current date and time
                    //Log::debug('Single Time Slot');
                    if(strtotime($reminder_time)==strtotime(date('Y-m-d H:i',strtotime($collect_time[0].'-'.$collect_time[1].'-'.$collect_time[2]))))  // After adding reminder time to current date and time if it is equal to collect time
                    {
                        $notification_check = item_order_notification::where('order_id',$order['id'])->where('status','0')->get(); // Fetching data of order from notification table
                        if(count($notification_check)=='0') // If notification table have not entry of this order id
                        {    Log::debug('Cron Job Runned ');
                            ///$data = ['order_id'=>]
                            $order_id = base64_encode($order['id']);
                            /*$sellerlink = '/my_sales/'.$order_id;
                            $buyerlink = '/my_order/'.$order_id;*/
                            $sellerlink = '/my_sales/';
                            $buyerlink = '/my_order/';
                            item_order_notification::create(['order_id'=> $order['id'],'user_id'=>$order['seller_id'],'link'=>$sellerlink,'status'=>'0','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s'),'type'=>'seller']);
                            item_order_notification::create(['order_id'=> $order['id'],'user_id'=>$order['user_id'],'link'=>$buyerlink,'status'=>'0','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s'),'type'=>'buyer']);
                        }
                        else    // If notification table have entry of this order id
                        {
                            if(strtotime(date('Y-m-d H:i')) >= strtotime(date('y-m-d h:i',strtotime($collect_time[0].'-'.$collect_time[1].'-'.$collect_time[2]))))
                            {
                                item_order_notification::where('order_id',$order['id'])->where('status','0')->update(['status'=>'1']);
                            }
                        }
                    }
                    if(strtotime(date('Y-m-d H:i')) >= strtotime(date('Y-m-d H:i',strtotime($collect_time[0].'-'.$collect_time[1].'-'.$collect_time[2]))))   //
                    {
                        item_order_notification::where('order_id',$order['id'])->where('status','0')->update(['status'=>'1']);
                    }

                }

            }
            else    // if it is single slot
            {

                $collect_time = explode('-',$order['o_collection_time']);
               date_default_timezone_set($user[0]['timezone']);
                $reminder_time = date("Y-m-d H:i", strtotime("+".$order['reminder_time']." minutes")); // Adding Remind Time Minutes to current date and time
                //Log::debug('Single Time Slot');
                 if(strtotime($reminder_time)==strtotime(date('Y-m-d H:i',strtotime($collect_time[0].'-'.$collect_time[1].'-'.$collect_time[2]))))  // After adding reminder time to current date and time if it is equal to collect time
                {
                    $notification_check = item_order_notification::where('order_id',$order['id'])->where('status','0')->get(); // Fetching data of order from notification table
                    if(count($notification_check)=='0') // If notification table have not entry of this order id
                    {    Log::debug('Cron Job Runned ');
                         ///$data = ['order_id'=>]
                        $order_id = base64_encode($order['id']);
                        /*$sellerlink = '/my_sales/'.$order_id;
                        $buyerlink = '/my_order/'.$order_id;*/
                        $sellerlink = '/my_sales/';
                        $buyerlink = '/my_order/';
                        item_order_notification::create(['order_id'=> $order['id'],'user_id'=>$order['seller_id'],'link'=>$sellerlink,'status'=>'0','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s'),'type'=>'seller']);
                        item_order_notification::create(['order_id'=> $order['id'],'user_id'=>$order['user_id'],'link'=>$buyerlink,'status'=>'0','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s'),'type'=>'buyer']);
                    }
                    else    // If notification table have entry of this order id
                    {
                        if(strtotime(date('Y-m-d H:i')) >= strtotime(date('y-m-d h:i',strtotime($collect_time[0].'-'.$collect_time[1].'-'.$collect_time[2]))))
                        {
                            item_order_notification::where('order_id',$order['id'])->where('status','0')->update(['status'=>'0']);
                        }
                    }
                }
                if(strtotime(date('Y-m-d H:i')) >= strtotime(date('Y-m-d H:i',strtotime($collect_time[0].'-'.$collect_time[1].'-'.$collect_time[2]))))   //
                {
                    item_order_notification::where('order_id',$order['id'])->where('status','0')->update(['status'=>'1']);
                }
            }

        }
        
    }
}
