<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use App\Order;
use App\User;
use App\Product;
use Braintree_Transaction;
use Braintree_Customer;
use Braintree_PaymentMethod;
use Braintree_PaymentMethodNonce;
use App\P_items_option;
use App\P_sell_option;
use App\P_service_option;
use App\P_subscription_option;
use App\P_type;
use App\P_room;
use App\Users_opening_hr;
use App\Country;
use App\Credit_detail;
use App\User_card;
use App\vat;
use App\system_setting;
use App\payment_method_token;
use App\Traits\TimezoneTrait;
use DB;

class SubscriptionAutoPay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:autopay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron Job For Auto Charging of card for Subscription';

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
        $subscriptions = Order::where('subs_status','1')->get();
        $order_id = mt_rand(10000000,99999999);
        $creditcleardate = system_setting::where('status','1')->get();
        $activedate = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+ ' . $creditcleardate[0]['clear_credit_period'] . ' days'));
        foreach($subscriptions as $subscriptionvalue)
        {
            $user = User::where('id',$subscriptionvalue['user_id'])->get();
            date_default_timezone_set($user[0]['timezone']);
            if($subscriptionvalue['p_price_per_optn']=='1') // if subscription is for day basis
            {
                $subscriptiondate = date('Y-m-d',strtotime($subscriptionvalue['created_at']));
                // $renewdate = date('Y-m-d',strtotime("+'".$subscriptionvalue['o_subs_period']."' months", strtotime($subscriptionvalue['created_at'])));
                $renewdate = date('Y-m-d', strtotime($subscriptiondate. ' + '.$subscriptionvalue['o_subs_period'].' days'));
                Log::debug('Renew Date:'.$renewdate);
                if(strtotime(date('Y-m-d')) > strtotime($renewdate))  // Renew the customer
                {
                    $product_detail = Product::where('id',$subscriptionvalue['o_product_id'])->get();
                    if(count($product_detail)>'0')       // if subscription exist
                    {
                        if($product_detail[0]['p_subs_price']!=$subscriptionvalue['o_price'])    // if subscription price is changed
                        {
                            $price = $subscriptionvalue['o_quantity'] * $subscriptionvalue['o_subs_period'] * $product_detail[0]['p_subs_price'];
                        }
                        else // if subscription price is not changed
                        {
                            $price = $subscriptionvalue['o_sub_total'];
                        }
                        if($user[0]['wallet']>=$price)
                        {
                            User::where('id',$user[0]['id'])->decrement('wallet',$price);
                            $status = 1;
                        }
                        else {
                            $payment_method_token = payment_method_token::where('user_id', $subscriptionvalue['user_id'])->get();
                            $nonce = Braintree_PaymentMethodNonce::create($payment_method_token[0]['payment_method_token']);
                            $nonce = $nonce->paymentMethodNonce->nonce;
                            $status = Braintree_Transaction::sale([
                                'amount' => $price,
                                'customerId' => $payment_method_token[0]['customer_id'],
                                'paymentMethodNonce' => $nonce,
                                'options' => [
                                    'submitForSettlement' => True,
                                    'storeInVaultOnSuccess' => true,

                                ]
                            ]);
                        }
                        if($status->success == 1 || $status == 1)
                        {
                            $insert_arr = [
                                'order_id'			=> 	$order_id,
                                'seller_id' 		=>	$subscriptionvalue['seller_id'],
                                'user_id'			=>	$subscriptionvalue['user_id'],
                                'braintree_id'		=>	$subscriptionvalue['braintree_id'],
                                'o_name'			=>  $subscriptionvalue['o_name'],
                                'o_email'			=>	$subscriptionvalue['o_email'],
                                'o_address' 		=>	$subscriptionvalue['o_address'],
                                'o_country'			=>	$subscriptionvalue['o_country'],
                                'o_postal_code'		=>	$subscriptionvalue['o_postal_code'],
                                'o_product_id'		=>	$subscriptionvalue['o_product_id'],
                                'o_product_type'	=>  $subscriptionvalue['o_product_type'],
                                'o_shipping_service' => NULL,
                                'o_currency'		=> $subscriptionvalue['o_currency'],
                                'o_sub_total'		=> $price,
                                'o_total'			=> $price, #changes when delivery added
                                'o_price'           =>  $product_detail[0]['p_subs_price'],
                                'o_quantity'		=> $subscriptionvalue['o_quantity'],
                                'o_purchased_for'	=> $subscriptionvalue['o_purchased_for'],
                                'o_delivery_charge' => $subscriptionvalue['o_delivery_charge'],
                                'o_delivery_provider' =>$subscriptionvalue['o_delivery_provider'],
                                'o_dispatched'		=>	0,
                                'o_dispatched_date' =>	NULL,
                                'o_returned' 		=>	0,
                                'o_returned_date' 	=>	NULL,
                                'o_cancelled' 		=>	0,
                                'o_cancelled_date' 	=>	NULL,
                                'o_tracking_no' 	=>	NULL,
                                'o_tracking_link' 	=>	NULL,
                                'o_collection_time' =>	$subscriptionvalue['o_collection_time'],
                                'o_lend_subscribe_starts' =>  NULL,
                                'o_lend_subscribe_ends'   =>  NULL,
                                'reminder_time'=>$subscriptionvalue['reminder_time'],
                                'o_subs_period'=>$subscriptionvalue['o_subs_period'],
                                'p_price_per_optn'=>$subscriptionvalue['p_price_per_optn'],
                                'subs_status'=>'1',
                                'created_at'=>date('Y-m-d H:i:s'),
                                'updated_at'=>date('Y-m-d H:i:s')
                            ];
                            $order_generated = DB::table('orders')->insertGetId($insert_arr);
                            if(!empty($order_generated)){
                                //$savepaymentmethodtoken = payment_method_token::create(['order_id'=>$order_generated,'payment_method_token'=>$payment_method_token[0]['payment_method_token'],'customer_id'=>$payment_method_token[0]['customer_id'],'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
                                Order::where('id',$subscriptionvalue['id'])->update(['subs_status'=>'0']);
                                if($subscriptionvalue['o_currency']=='GBP' || $subscriptionvalue['o_currency']=='EUR')
                                {
                                    $vatamount = $price;
                                    $fee_net = $vatamount * 0.15;
                                    $fee_gross = $fee_net / 1.2;
                                    $fee_vat = $fee_net - $fee_gross;
                                }
                                else
                                {
                                    $vatamount = $price;
                                    $fee_gross = $price;
                                    $fee_vat = '0';
                                    $fee_net = $price;
                                }
                                vat::create(['order_id'=>$order_generated,
                                    'amount'=>$vatamount,
                                    'fee_gross'=>$fee_gross,
                                    'fee_vat'=>$fee_vat,
                                    'fee_net'=>$fee_net,
                                    'created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s')]);
                                $details_arr = [
                                    'user_id'	=>$subscriptionvalue['seller_id'],
                                    'order_id'	=>$order_generated,
                                    'value'		=>$price,
                                    'added_on'	=>date('Y-m-d H:i:s'),
                                    'active_on' =>$activedate,
                                    'created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                ];
                                $credit_detail_id = DB::table('credit_details')->insert($details_arr);
                                $history_arr = [
                                    'user_id'	=>$subscriptionvalue['seller_id'],
                                    'order_id'	=>$order_generated,
                                    'amount'	=>$price,
                                    'comment'	=>1,
                                    'currency'  =>$subscriptionvalue['o_currency'],
                                    'created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s')
                                ];
                                $credit_hostory_id = DB::table('credit_histories')->insert($history_arr);
                            }

                        }
                        else
                        {
                            Log::debug('Transaction Failed');
                        }

                    }
                    else // if subscription not exist
                    {

                    }
                }
            }
            else if($subscriptionvalue['p_price_per_optn']=='2') // if subscription is for weekly basis
            {
                $subscriptiondate = date('Y-m-d',strtotime($subscriptionvalue['created_at']));
                // $renewdate = date('Y-m-d',strtotime("+'".$subscriptionvalue['o_subs_period']."' months", strtotime($subscriptionvalue['created_at'])));
                $period = $subscriptionvalue['o_subs_period'] * 7;
                $renewdate = date('Y-m-d', strtotime($subscriptiondate. ' + '.$period.' days'));
                Log::debug('Renew Date:'.$renewdate);
                if(strtotime(date('Y-m-d')) > strtotime($renewdate))  // Renew the customer
                {
                    $product_detail = Product::where('id',$subscriptionvalue['o_product_id'])->get();
                    if(count($product_detail)>'0')       // if subscription exist
                    {
                        if($product_detail[0]['p_subs_price']!=$subscriptionvalue['o_price'])    // if subscription price is changed
                        {
                            $price = $subscriptionvalue['o_quantity'] * $subscriptionvalue['o_subs_period'] * $product_detail[0]['p_subs_price'];
                        }
                        else // if subscription price is not changed
                        {
                            $price = $subscriptionvalue['o_sub_total'];
                        }
                        if($user[0]['wallet']>=$price)
                        {
                            User::where('id',$user[0]['id'])->decrement('wallet',$price);
                            $status = 1;
                        }
                        else {

                            $payment_method_token = payment_method_token::where('user_id', $subscriptionvalue['user_id'])->get();
                            $nonce = Braintree_PaymentMethodNonce::create($payment_method_token[0]['payment_method_token']);
                            $nonce = $nonce->paymentMethodNonce->nonce;
                            $status = Braintree_Transaction::sale([
                                'amount' => $price,
                                'customerId' => $payment_method_token[0]['customer_id'],
                                'paymentMethodNonce' => $nonce,
                                'options' => [
                                    'submitForSettlement' => True,
                                    'storeInVaultOnSuccess' => true,

                                ]
                            ]);
                        }
                        if($status->success == 1 || $status == 1)
                        {
                            $insert_arr = [
                                'order_id'			=> 	$order_id,
                                'seller_id' 		=>	$subscriptionvalue['seller_id'],
                                'user_id'			=>	$subscriptionvalue['user_id'],
                                'braintree_id'		=>	$subscriptionvalue['braintree_id'],
                                'o_name'			=>  $subscriptionvalue['o_name'],
                                'o_email'			=>	$subscriptionvalue['o_email'],
                                'o_address' 		=>	$subscriptionvalue['o_address'],
                                'o_country'			=>	$subscriptionvalue['o_country'],
                                'o_postal_code'		=>	$subscriptionvalue['o_postal_code'],
                                'o_product_id'		=>	$subscriptionvalue['o_product_id'],
                                'o_product_type'	=>  $subscriptionvalue['o_product_type'],
                                'o_shipping_service' => NULL,
                                'o_currency'		=> $subscriptionvalue['o_currency'],
                                'o_sub_total'		=> $price,
                                'o_total'			=> $price, #changes when delivery added
                                'o_price'           =>  $product_detail[0]['p_subs_price'],
                                'o_quantity'		=> $subscriptionvalue['o_quantity'],
                                'o_purchased_for'	=> $subscriptionvalue['o_purchased_for'],
                                'o_delivery_charge' => $subscriptionvalue['o_delivery_charge'],
                                'o_delivery_provider' =>$subscriptionvalue['o_delivery_provider'],
                                'o_dispatched'		=>	0,
                                'o_dispatched_date' =>	NULL,
                                'o_returned' 		=>	0,
                                'o_returned_date' 	=>	NULL,
                                'o_cancelled' 		=>	0,
                                'o_cancelled_date' 	=>	NULL,
                                'o_tracking_no' 	=>	NULL,
                                'o_tracking_link' 	=>	NULL,
                                'o_collection_time' =>	$subscriptionvalue['o_collection_time'],
                                'o_lend_subscribe_starts' =>  NULL,
                                'o_lend_subscribe_ends'   =>  NULL,
                                'reminder_time'=>$subscriptionvalue['reminder_time'],
                                'o_subs_period'=>$subscriptionvalue['o_subs_period'],
                                'p_price_per_optn'=>$subscriptionvalue['p_price_per_optn'],
                                'subs_status'=>'1',
                                'created_at'=>date('Y-m-d H:i:s'),
                                'updated_at'=>date('Y-m-d H:i:s')
                            ];
                            $order_generated = DB::table('orders')->insertGetId($insert_arr);
                            if(!empty($order_generated)){
                                //$savepaymentmethodtoken = payment_method_token::create(['order_id'=>$order_generated,'payment_method_token'=>$payment_method_token[0]['payment_method_token'],'customer_id'=>$payment_method_token[0]['customer_id'],'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
                                Order::where('id',$subscriptionvalue['id'])->update(['subs_status'=>'0']);
                                if($subscriptionvalue['o_currency']=='GBP' || $subscriptionvalue['o_currency']=='EUR')
                                {
                                    $vatamount = $price;
                                    $fee_net = $vatamount * 0.15;
                                    $fee_gross = $fee_net / 1.2;
                                    $fee_vat = $fee_net - $fee_gross;
                                }
                                else
                                {
                                    $vatamount = $price;
                                    $fee_gross = $price;
                                    $fee_vat = '0';
                                    $fee_net = $price;
                                }
                                vat::create(['order_id'=>$order_generated,
                                    'amount'=>$vatamount,
                                    'fee_gross'=>$fee_gross,
                                    'fee_vat'=>$fee_vat,
                                    'fee_net'=>$fee_net,
                                    'created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s')]);
                                $details_arr = [
                                    'user_id'	=>$subscriptionvalue['seller_id'],
                                    'order_id'	=>$order_generated,
                                    'value'		=>$price,
                                    'added_on'	=>date('Y-m-d H:i:s'),
                                    'active_on' =>$activedate,
                                    'created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                ];
                                $credit_detail_id = DB::table('credit_details')->insert($details_arr);
                                $history_arr = [
                                    'user_id'	=>$subscriptionvalue['seller_id'],
                                    'order_id'	=>$order_generated,
                                    'amount'	=>$price,
                                    'comment'	=>1,
                                    'currency'  =>$subscriptionvalue['o_currency'],
                                    'created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s')
                                ];
                                $credit_hostory_id = DB::table('credit_histories')->insert($history_arr);
                            }

                        }
                        else
                        {
                            Log::debug('Transaction Failed');
                        }

                    }
                    else // if subscription not exist
                    {

                    }
                }
            }
            else if($subscriptionvalue['p_price_per_optn']=='3') // if subscription is for monthly basis
            {
                $subscriptiondate = date('Y-m-d',strtotime($subscriptionvalue['created_at']));
               // $renewdate = date('Y-m-d',strtotime("+'".$subscriptionvalue['o_subs_period']."' months", strtotime($subscriptionvalue['created_at'])));
                $renewdate = date('Y-m-d', strtotime($subscriptiondate. ' + '.$subscriptionvalue['o_subs_period'].' months'));
                Log::debug('Renew Date:'.$renewdate);
               if(strtotime(date('Y-m-d')) > strtotime($renewdate))  // Renew the customer
               {
                   $product_detail = Product::where('id',$subscriptionvalue['o_product_id'])->get();
                   if(count($product_detail)>'0')       // if subscription exist
                   {
                       if($product_detail[0]['p_subs_price']!=$subscriptionvalue['o_price'])    // if subscription price is changed
                       {
                            $price = $subscriptionvalue['o_quantity'] * $subscriptionvalue['o_subs_period'] * $product_detail[0]['p_subs_price'];
                       }
                       else // if subscription price is not changed
                       {
                            $price = $subscriptionvalue['o_sub_total'];
                       }
                       if($user[0]['wallet']>=$price)
                       {
                           User::where('id',$user[0]['id'])->decrement('wallet',$price);
                           $status = 1;
                       }
                       else {
                           $payment_method_token = payment_method_token::where('user_id', $subscriptionvalue['user_id'])->get();
                           $nonce = Braintree_PaymentMethodNonce::create($payment_method_token[0]['payment_method_token']);
                           $nonce = $nonce->paymentMethodNonce->nonce;
                           $status = Braintree_Transaction::sale([
                               'amount' => $price,
                               'customerId' => $payment_method_token[0]['customer_id'],
                               'paymentMethodNonce' => $nonce,
                               'options' => [
                                   'submitForSettlement' => True,
                                   'storeInVaultOnSuccess' => true,

                               ]
                           ]);
                       }
                       if($status->success == 1 || $status == 1)
                       {
                           $insert_arr = [
                               'order_id'			=> 	$order_id,
                               'seller_id' 		=>	$subscriptionvalue['seller_id'],
                               'user_id'			=>	$subscriptionvalue['user_id'],
                               'braintree_id'		=>	$subscriptionvalue['braintree_id'],
                               'o_name'			=>  $subscriptionvalue['o_name'],
                               'o_email'			=>	$subscriptionvalue['o_email'],
                               'o_address' 		=>	$subscriptionvalue['o_address'],
                               'o_country'			=>	$subscriptionvalue['o_country'],
                               'o_postal_code'		=>	$subscriptionvalue['o_postal_code'],
                               'o_product_id'		=>	$subscriptionvalue['o_product_id'],
                               'o_product_type'	=>  $subscriptionvalue['o_product_type'],
                               'o_shipping_service' => NULL,
                               'o_currency'		=> $subscriptionvalue['o_currency'],
                               'o_sub_total'		=> $price,
                               'o_total'			=> $price, #changes when delivery added
                               'o_price'           =>  $product_detail[0]['p_subs_price'],
                               'o_quantity'		=> $subscriptionvalue['o_quantity'],
                               'o_purchased_for'	=> $subscriptionvalue['o_purchased_for'],
                               'o_delivery_charge' => $subscriptionvalue['o_delivery_charge'],
                               'o_delivery_provider' =>$subscriptionvalue['o_delivery_provider'],
                               'o_dispatched'		=>	0,
                               'o_dispatched_date' =>	NULL,
                               'o_returned' 		=>	0,
                               'o_returned_date' 	=>	NULL,
                               'o_cancelled' 		=>	0,
                               'o_cancelled_date' 	=>	NULL,
                               'o_tracking_no' 	=>	NULL,
                               'o_tracking_link' 	=>	NULL,
                               'o_collection_time' =>	$subscriptionvalue['o_collection_time'],
                               'o_lend_subscribe_starts' =>  NULL,
                               'o_lend_subscribe_ends'   =>  NULL,
                               'reminder_time'=>$subscriptionvalue['reminder_time'],
                               'o_subs_period'=>$subscriptionvalue['o_subs_period'],
                               'p_price_per_optn'=>$subscriptionvalue['p_price_per_optn'],
                               'subs_status'=>'1',
                               'created_at'=>date('Y-m-d H:i:s'),
                               'updated_at'=>date('Y-m-d H:i:s')
                           ];
                           $order_generated = DB::table('orders')->insertGetId($insert_arr);
                           if(!empty($order_generated)){
                               //$savepaymentmethodtoken = payment_method_token::create(['order_id'=>$order_generated,'payment_method_token'=>$payment_method_token[0]['payment_method_token'],'customer_id'=>$payment_method_token[0]['customer_id'],'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
                               Order::where('id',$subscriptionvalue['id'])->update(['subs_status'=>'0']);
                               if($subscriptionvalue['o_currency']=='GBP' || $subscriptionvalue['o_currency']=='EUR')
                               {
                                   $vatamount = $price;
                                   $fee_net = $vatamount * 0.15;
                                   $fee_gross = $fee_net / 1.2;
                                   $fee_vat = $fee_net - $fee_gross;
                               }
                               else
                               {
                                   $vatamount = $price;
                                   $fee_gross = $price;
                                   $fee_vat = '0';
                                   $fee_net = $price;
                               }
                               vat::create(['order_id'=>$order_generated,
                                   'amount'=>$vatamount,
                                   'fee_gross'=>$fee_gross,
                                   'fee_vat'=>$fee_vat,
                                   'fee_net'=>$fee_net,
                                   'created_at'=>date('Y-m-d H:i:s'),
                                   'updated_at'=>date('Y-m-d H:i:s')]);
                               $details_arr = [
                                   'user_id'	=>$subscriptionvalue['seller_id'],
                                   'order_id'	=>$order_generated,
                                   'value'		=>$price,
                                   'added_on'	=>date('Y-m-d H:i:s'),
                                   'active_on' =>$activedate,
                                   'created_at'=>date('Y-m-d H:i:s'),
                                   'updated_at'=>date('Y-m-d H:i:s'),
                               ];
                               $credit_detail_id = DB::table('credit_details')->insert($details_arr);
                               $history_arr = [
                                   'user_id'	=>$subscriptionvalue['seller_id'],
                                   'order_id'	=>$order_generated,
                                   'amount'	=>$price,
                                   'comment'	=>1,
                                   'currency'  =>$subscriptionvalue['o_currency'],
                                   'created_at'=>date('Y-m-d H:i:s'),
                                   'updated_at'=>date('Y-m-d H:i:s')
                               ];
                               $credit_hostory_id = DB::table('credit_histories')->insert($history_arr);
                           }

                       }
                       else
                       {
                           Log::debug('Transaction Failed');
                       }

                   }
                   else // if subscription not exist
                   {

                   }
               }
            }
            else if($subscriptionvalue['p_price_per_optn']=='4') // if subscription is for yearly basis
            {
                $subscriptiondate = date('Y-m-d',strtotime($subscriptionvalue['created_at']));
                // $renewdate = date('Y-m-d',strtotime("+'".$subscriptionvalue['o_subs_period']."' months", strtotime($subscriptionvalue['created_at'])));
                $renewdate = date('Y-m-d', strtotime($subscriptiondate. ' + '.$subscriptionvalue['o_subs_period'].' year'));
                Log::debug('Renew Date:'.$renewdate);
                if(strtotime(date('Y-m-d')) > strtotime($renewdate))  // Renew the customer
                {
                    $product_detail = Product::where('id',$subscriptionvalue['o_product_id'])->get();
                    if(count($product_detail)>'0')       // if subscription exist
                    {
                        if($product_detail[0]['p_subs_price']!=$subscriptionvalue['o_price'])    // if subscription price is changed
                        {
                            $price = $subscriptionvalue['o_quantity'] * $subscriptionvalue['o_subs_period'] * $product_detail[0]['p_subs_price'];
                        }
                        else // if subscription price is not changed
                        {
                            $price = $subscriptionvalue['o_sub_total'];
                        }
                        if($user[0]['wallet']>=$price)
                        {
                            User::where('id',$user[0]['id'])->decrement('wallet',$price);
                            $status = 1;
                        }
                        else {
                            $payment_method_token = payment_method_token::where('user_id', $subscriptionvalue['user_id'])->get();
                            $nonce = Braintree_PaymentMethodNonce::create($payment_method_token[0]['payment_method_token']);
                            $nonce = $nonce->paymentMethodNonce->nonce;
                            $status = Braintree_Transaction::sale([
                                'amount' => $price,
                                'customerId' => $payment_method_token[0]['customer_id'],
                                'paymentMethodNonce' => $nonce,
                                'options' => [
                                    'submitForSettlement' => True,
                                    'storeInVaultOnSuccess' => true,

                                ]
                            ]);
                        }
                        if($status->success == 1 || $status == 1)
                        {
                            $insert_arr = [
                                'order_id'			=> 	$order_id,
                                'seller_id' 		=>	$subscriptionvalue['seller_id'],
                                'user_id'			=>	$subscriptionvalue['user_id'],
                                'braintree_id'		=>	$subscriptionvalue['braintree_id'],
                                'o_name'			=>  $subscriptionvalue['o_name'],
                                'o_email'			=>	$subscriptionvalue['o_email'],
                                'o_address' 		=>	$subscriptionvalue['o_address'],
                                'o_country'			=>	$subscriptionvalue['o_country'],
                                'o_postal_code'		=>	$subscriptionvalue['o_postal_code'],
                                'o_product_id'		=>	$subscriptionvalue['o_product_id'],
                                'o_product_type'	=>  $subscriptionvalue['o_product_type'],
                                'o_shipping_service' => NULL,
                                'o_currency'		=> $subscriptionvalue['o_currency'],
                                'o_sub_total'		=> $price,
                                'o_total'			=> $price, #changes when delivery added
                                'o_price'           =>  $product_detail[0]['p_subs_price'],
                                'o_quantity'		=> $subscriptionvalue['o_quantity'],
                                'o_purchased_for'	=> $subscriptionvalue['o_purchased_for'],
                                'o_delivery_charge' => $subscriptionvalue['o_delivery_charge'],
                                'o_delivery_provider' =>$subscriptionvalue['o_delivery_provider'],
                                'o_dispatched'		=>	0,
                                'o_dispatched_date' =>	NULL,
                                'o_returned' 		=>	0,
                                'o_returned_date' 	=>	NULL,
                                'o_cancelled' 		=>	0,
                                'o_cancelled_date' 	=>	NULL,
                                'o_tracking_no' 	=>	NULL,
                                'o_tracking_link' 	=>	NULL,
                                'o_collection_time' =>	$subscriptionvalue['o_collection_time'],
                                'o_lend_subscribe_starts' =>  NULL,
                                'o_lend_subscribe_ends'   =>  NULL,
                                'reminder_time'=>$subscriptionvalue['reminder_time'],
                                'o_subs_period'=>$subscriptionvalue['o_subs_period'],
                                'p_price_per_optn'=>$subscriptionvalue['p_price_per_optn'],
                                'subs_status'=>'1',
                                'created_at'=>date('Y-m-d H:i:s'),
                                'updated_at'=>date('Y-m-d H:i:s')
                            ];
                            $order_generated = DB::table('orders')->insertGetId($insert_arr);
                            if(!empty($order_generated)){
                                //$savepaymentmethodtoken = payment_method_token::create(['order_id'=>$order_generated,'payment_method_token'=>$payment_method_token[0]['payment_method_token'],'customer_id'=>$payment_method_token[0]['customer_id'],'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
                                Order::where('id',$subscriptionvalue['id'])->update(['subs_status'=>'0']);
                                if($subscriptionvalue['o_currency']=='GBP' || $subscriptionvalue['o_currency']=='EUR')
                                {
                                    $vatamount = $price;
                                    $fee_net = $vatamount * 0.15;
                                    $fee_gross = $fee_net / 1.2;
                                    $fee_vat = $fee_net - $fee_gross;
                                }
                                else
                                {
                                    $vatamount = $price;
                                    $fee_gross = $price;
                                    $fee_vat = '0';
                                    $fee_net = $price;
                                }
                                vat::create(['order_id'=>$order_generated,
                                    'amount'=>$vatamount,
                                    'fee_gross'=>$fee_gross,
                                    'fee_vat'=>$fee_vat,
                                    'fee_net'=>$fee_net,
                                    'created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s')]);
                                $details_arr = [
                                    'user_id'	=>$subscriptionvalue['seller_id'],
                                    'order_id'	=>$order_generated,
                                    'value'		=>$price,
                                    'added_on'	=>date('Y-m-d H:i:s'),
                                    'active_on' =>$activedate,
                                    'created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                ];
                                $credit_detail_id = DB::table('credit_details')->insert($details_arr);
                                $history_arr = [
                                    'user_id'	=>$subscriptionvalue['seller_id'],
                                    'order_id'	=>$order_generated,
                                    'amount'	=>$price,
                                    'comment'	=>1,
                                    'currency'  =>$subscriptionvalue['o_currency'],
                                    'created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s')
                                ];
                                $credit_hostory_id = DB::table('credit_histories')->insert($history_arr);
                            }

                        }
                        else
                        {
                            Log::debug('Transaction Failed');
                        }

                    }
                    else // if subscription not exist
                    {

                    }
                }
            }
        }

        //Log::debug('Subscription auto pay called');

    }
}
