<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use Input;
use Redirect;
use Response;
use DB;
use View;
use Guard;
use Braintree_Transaction;
use Braintree_Customer;
use Braintree_PaymentMethod;
use Braintree_PaymentMethodNonce;
use App\User;
use App\Role;
use App\P_items_option;
use App\Product;
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


class PaymentsController extends Controller
{
    //
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	use TimezoneTrait;
	
	public function __construct()
	{
		$this->middleware(['auth', 'verified','checkLoginRole']);
		$this->middleware(function ($request, $next) {

		 // fetch your session here 

		 $this->verifytimezone(Auth::user()->timezone);
		 return $next($request);

		});
	}


	/*
	Payment process : product page
	*/
    public function process(Request $request)
	{
		$date = $this->summertime();


		try{
			parse_str($request['formData'], $formDetails);
			$first_name = Auth::user()->name;
			$email = Auth::user()->email;
			$phone = Auth::user()->contact_code.''.Auth::user()->contact_no;

			#chec if product is not out of stock
			$product_id = $request['product_id'];

			$quanity_val = Product::where('id',$product_id)->value('p_quantity');
			if($quanity_val < 1){
				$response['error'] = 5;
				return response()->json($response);
				//die;
			}
			if(!empty($request['braintree_id'])){  #existing customer/card
				$customer_success = 1;
				$customer_id = $request['braintree_id'];

			}else{ #new customer
				#create customer
				$customer = Braintree_Customer::create([
					'firstName' => $first_name, 
					'lastName' 	=> $first_name,
					'email'		=> $email,
					'phone' 	=> $phone,
				]);
				if ($customer->success) {
					$customer_success = 1;
					$customer_id = $customer->customer->id;
				}else{
					$customer_success = 0;

				}
				/*$response['message'] = $customer_success;
				$response['success'] = '1';
				return response()->json($response);*/
			}
			 if ($customer_success == 1) {
	        	#create customer payment tye
	        	$payload = $request->input('payload', false);
			    $amount = $request['def_gbp_amount'];

			    $nonce = $payload['nonce'];
			    $method_result = Braintree_PaymentMethod::create([
				    'customerId' => $customer_id,
				    'paymentMethodNonce' => $nonce
				]);
			   $payment_method_token = $method_result->paymentMethod->token;

				if($method_result->success){
					$status = Braintree_Transaction::sale([
						'amount' => $amount,
						'customerId' => $customer_id,
						//'paymentMethodNonce' => $nonce,
						'options' => [
						    'submitForSettlement' => True,
						    'storeInVaultOnSuccess' => true,

						]
				    ]);
				    if($status->success == 1){
				    	#payment successfull

				    	if(empty($request['braintree_id'])){
				    		#insert into user card
					    	$insert_arr = [
					    		'user_id'					=> $user_id = Auth::id(), 
					    		'braintree_customer_id'		=> $customer->customer->id,
					    		'created_at'				=> $date,
				       			'updated_at'				=> $date,
					    	];
							$check_transition = DB::table('user_cards')->insert($insert_arr);
							if(empty($check_transition)){

								$response['error'] = 111;
								return response()->json($response);
								
							}else{
								return response()->json($this->saveOrder($formDetails, $customer->customer->id, $request['delivery_provider'],$payment_method_token,$request['def_gbp_amount']));
							}
				    	}else{
				    		return response()->json($this->saveOrder($formDetails, $request['braintree_id'], $request['delivery_provider'],$payment_method_token,$request['def_gbp_amount']));
				    	}

				    }else{
				    	$response['error'] = 2;
				    	return response()->json($status);
				    }

				}else{
					$response['error'] = 3;
					return response()->json($method_result);
				}
	        }else{
	        	$response['error'] = 4;
	        	return response()->json($response);
	        }
		    
	    }catch(\Exception $e){
			return response()->json($e->getMEssage());
		}
	}  #process

	/*
	Save order
	*/
	public function saveOrder($formDetails, $braintree_id , $delivery_provider,$payment_method_token,$def_gbp_amount)
	{
	    //dd($formDetails['p_price_per_optn']);
		$date = $this->summertime();
		$creditcleardate = system_setting::where('status','1')->get();
		$reminder_time = $formDetails['remindertime'];
		if($formDetails['product_type']=='3')
        {
            $o_subs_period = $formDetails['o_subs_period'];
            $p_price_per_optn = $formDetails['p_price_per_optn'];
            $subs_status = '1';
            $o_price = $formDetails['subscribe_price'];
        }
		else
        {
            $o_subs_period = '0';
            $p_price_per_optn = '0';
            $subs_status = '0';
            $o_price = '';
        }
		if($formDetails['product_type']=='1' || $formDetails['product_type']=='3') {
            $activedate = date('Y-m-d H:i:s', strtotime($date . '+ ' . $creditcleardate[0]['clear_credit_period'] . ' days'));
        }
		if($formDetails['product_type']=='2')
        {
            $activedate = date('Y-m-d H:i:s', strtotime($date . '+ ' . $creditcleardate[0]['clear_credit_period_service'] . ' days'));
        }
		//print_r($formDetails);die;
		try{
			$order_id = mt_rand(10000000,99999999);		
			$userId = Auth::id();
			$o_name= Auth::user()->name;
			$o_email = Auth::user()->email;
			$o_address = Auth::user()->street_address1." ".Auth::user()->street_address1;
			$o_country = Auth::user()->country;
			$o_postal_code = Auth::user()->pincode;
			if(isset($formDetails['slot_time']) && !isset($formDetails['service_slot_time']))
			{
				$collection_time = $formDetails['slot_time'];
			}
			elseif(isset($formDetails['service_slot_time']) && !isset($formDetails['slot_time']))
			{
				$collection_time = implode(',',$formDetails['service_slot_time']);
			}
			else
			{
				$collection_time = '';
			}
			
			if(!empty($collection_time)){
				
				if(isset($formDetails['slot_time']))
				{
				$coll_data = explode('|', $collection_time);
				$coll_date = $coll_data[0];
				$coll_time = $coll_data[1];

				$time_arr = explode('-', $coll_time);
				$start_time = date("H:i", strtotime($time_arr[0]));
				$end_time = date("H:i", strtotime($time_arr[1]));
				
				$o_collection_time = $coll_date.''.$start_time.' - '.$coll_date.''.$end_time;
				#current time : date('Y-m-d H:i')
				}
				else
				{
					$collection = explode(',',$collection_time);
					$o_collection_time_array;
					$length = count($collection);
					$o_collection_time = '';
					for($i=0;$i<$length;$i++)
					{
						$coll_data = explode('|', $collection[$i]);
						$coll_date = $coll_data[0];
						$coll_time = $coll_data[1];
						$time_arr = explode('-', $coll_time);
						$start_time = date("H:i", strtotime($time_arr[0]));
						$end_time = date("H:i", strtotime($time_arr[1]));
						$o_collection_time_array[$i] = $coll_date.''.$start_time.' - '.$coll_date.''.$end_time;
						if($i==$length)
						{

						}

					}
					$o_collection_time = implode(',',$o_collection_time_array);
				    //$o_collection_time = implode(',',$o_collection_time_array);
					#current time : date('Y-m-d H:i')
				}
			}else{
				$o_collection_time = NULL;
			}
			$amount = $formDetails['purchase_val'];    // Amount of product
			$deliverycharge = $formDetails['delivery_charge']; // Delivery charge of product
			$totalamount = $amount + $deliverycharge; // total amount including delivery charge
			if($delivery_provider=='')
			{
				$delivery_provider = '0';
			}
			$insert_arr = [
				'order_id'			=> 	$order_id,
				'seller_id' 		=>	$formDetails['seller_id'],
				'user_id'			=>	$userId,
				'braintree_id'		=>	$braintree_id,
				'o_name'			=>	$o_name,
				'o_email'			=>	$o_email,
				'o_address' 		=>	$o_address,
				'o_country'			=>	$o_country,
				'o_postal_code'		=>	$o_postal_code,
				'o_product_id'		=>	$formDetails['product_id'],
				'o_product_type'	=>  $formDetails['product_type'],
				'o_shipping_service' => NULL,
				'o_currency'		=> $formDetails['currency'],
				'o_sub_total'		=> $formDetails['purchase_val'],
				'o_total'			=> $totalamount, #changes when delivery added
                'o_price'           =>  $o_price,
				'o_quantity'		=> $formDetails['quantity'],
				'o_purchased_for'	=> $formDetails['purchase_val'],
				'o_delivery_charge' => $deliverycharge,
				'o_delivery_provider' =>$delivery_provider,
				'o_dispatched'		=>	0,
				'o_dispatched_date' =>	NULL,
				'o_returned' 		=>	0,
				'o_returned_date' 	=>	NULL,
				'o_cancelled' 		=>	0,
				'o_cancelled_date' 	=>	NULL,
				'o_tracking_no' 	=>	NULL,
				'o_tracking_link' 	=>	NULL,
				'o_collection_time' =>	$o_collection_time,
				'o_lend_subscribe_starts' =>  NULL,
				'o_lend_subscribe_ends'   =>  NULL,
                'reminder_time'=>$reminder_time,
                'o_subs_period'=>$o_subs_period,
                'p_price_per_optn'=>$p_price_per_optn,
                'subs_status'=>$subs_status,
				'created_at'=>$date,
		        'updated_at'=>$date
			];

			$order_generated = DB::table('orders')->insertGetId($insert_arr);

			if(!empty($order_generated)){
				#insert into credit details
				/*$credit_detail_id = Credit_detail::create([
					'user_id'	=>$formDetails['seller_id'],
					'order_id'	=>$order_generated,
					'value'		=>$formDetails['purchase_val'],
					'added_on'	=>date('Y-m-d h:i:s'),
					'created_at'=>date('Y-m-d h:i:s'),
		        	'updated_at'=>date('Y-m-d h:i:s'),
				])->id;*/
				# Insert into vat table
				if($formDetails['currency']=='GBP' || $formDetails['currency']=='EUR')
				{
					$vatamount = $formDetails['purchase_val'];
					$fee_net = $vatamount * 0.15;
					$fee_gross = $fee_net / 1.2;
					$fee_vat = $fee_net - $fee_gross;
				}
				else
				{
					$vatamount = $formDetails['purchase_val'];
					$fee_gross = $formDetails['purchase_val'];
					$fee_vat = '0';
					$fee_net = $formDetails['purchase_val'];
				}
				vat::create(['order_id'=>$order_generated,
								'amount'=>$vatamount,
								'fee_gross'=>$fee_gross,
								'fee_vat'=>$fee_vat,
								'fee_net'=>$fee_net,
								'created_at'=>$date,
								'updated_at'=>$date]);

				$details_arr = [
					'user_id'	=>$formDetails['seller_id'],
					'order_id'	=>$order_generated,
                    'currency'  =>$formDetails['currency'],
                    'def_gbp_amount'=>$def_gbp_amount,
					'value'		=>$formDetails['purchase_val'],
					'added_on'	=>$date,
                    'active_on' =>$activedate,
					'created_at'=>$date,
		        	'updated_at'=>$date,
				];
				$credit_detail_id = DB::table('credit_details')->insert($details_arr);
				#insert into credit details histories
				$history_arr = [
					'user_id'	=>$formDetails['seller_id'],
					'order_id'	=>$order_generated,
					'amount'	=>$formDetails['purchase_val'],
					'comment'	=>1,
					'currency'  =>$formDetails['currency'],
					'created_at'=>$date,
		        	'updated_at'=>$date
				];
				$credit_hostory_id = DB::table('credit_histories')->insert($history_arr);
				$paymentmethodtokencheck = payment_method_token::where('user_id',Auth::user()->id)->get();

				if(empty($credit_detail_id) || empty($credit_hostory_id)){
					$response['error'] = 12;
					return $response;
				}else{
                    if(count($paymentmethodtokencheck)=='0')
                    {
                        $savepaymentmethodtoken = payment_method_token::create(['user_id'=>Auth::user()->id,'payment_method_token'=>$payment_method_token,'customer_id'=>$braintree_id,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
                    }
					#managing inventory
					$old_quantity = Product::where('id',$formDetails['product_id'])->value('p_quantity');
					$new_quantity = $old_quantity-$formDetails['quantity'];
					if($new_quantity < 0){
						$new_quantity = 0;
					}else{
						$new_quantity = $new_quantity;
					}

					#update new quantity
					$update_q = DB::table('products')->where('id',$formDetails['product_id'])->update(['p_quantity'=>$new_quantity]);
					$order_generated = base64_encode($order_generated);
					$response['success'] = 1;
					$response['order_id'] = $order_generated;
					return $response;
				}

			}else{
				$response['error'] = 13;
				return $response;

			}
		}catch(\Exception $e){
			$response['error'] = $e->getMessage();
			return $response;
		}
		
	}
	public function freeorder(Request $request)
	{
		//print_r($formDetails);die;
		$date = $this->summertime();
		$activedate = '';
        $creditcleardate = system_setting::where('status','1')->get();
        if($request['product_type']=='1' || $request['product_type']=='3') {
            $activedate = date('Y-m-d H:i:s', strtotime($date . '+ ' . $creditcleardate[0]['clear_credit_period'] . ' days'));
        }
        if($request['product_type']=='2')
        {
            $activedate = date('Y-m-d H:i:s', strtotime($date . '+ ' . $creditcleardate[0]['clear_credit_period_service'] . ' days'));
        }
		try{
			$order_id = mt_rand(10000000,99999999);		
			$userId = Auth::id();
			$o_name= Auth::user()->name;
			$o_email = Auth::user()->email;
			$o_address = Auth::user()->street_address1." ".Auth::user()->street_address1;
			$o_country = Auth::user()->country;
			$o_postal_code = Auth::user()->pincode;
			$reminder_time = $request['remindertime'];
			if(isset($request['slot_time']) && !isset($request['service_slot_time']))
			{
				$collection_time = $request['slot_time'];
			}
			elseif(isset($request['service_slot_time']) && !isset($request['slot_time']))
			{
				$collection_time = implode(',',$request['service_slot_time']);
			}
			else
			{
				$collection_time = '';
			}
			
			if(!empty($collection_time)){
				
				if(isset($request['slot_time']))
				{
				$coll_data = explode('|', $collection_time);
				$coll_date = $coll_data[0];
				$coll_time = $coll_data[1];

				$time_arr = explode('-', $coll_time);
				$start_time = date("H:i", strtotime($time_arr[0]));
				$end_time = date("H:i", strtotime($time_arr[1]));
				
				$o_collection_time = $coll_date.''.$start_time.' - '.$coll_date.''.$end_time;
				#current time : date('Y-m-d H:i')
				}
				else
				{
					$collection = explode(',',$collection_time);
					$o_collection_time_array;
					$length = count($collection);
					$o_collection_time = '';
					for($i=0;$i<$length;$i++)
					{
						$coll_data = explode('|', $collection[$i]);
						
						$coll_date = $coll_data[0];
						$coll_time = $coll_data[1];
						$time_arr = explode('-', $coll_time);
						$start_time = date("H:i", strtotime($time_arr[0]));
						$end_time = date("H:i", strtotime($time_arr[1]));
						$o_collection_time_array[$i] = $coll_date.''.$start_time.' - '.$coll_date.''.$end_time;
						if($i==$length)
						{

						}

					}
					$o_collection_time = implode(',',$o_collection_time_array);
				    //$o_collection_time = implode(',',$o_collection_time_array);
					#current time : date('Y-m-d H:i')
				}
			}else{
				$o_collection_time = NULL;
			}
			
			$deliverycharge = '0';
			$delivery_provider = '0';
			$insert_arr = [
				'order_id'			=> 	$order_id,
				'seller_id' 		=>	$request['seller_id'],
				'user_id'			=>	$userId,
				'braintree_id'		=>	'',
				'o_name'			=>	$o_name,
				'o_email'			=>	$o_email,
				'o_address' 		=>	$o_address,
				'o_country'			=>	$o_country,
				'o_postal_code'		=>	$o_postal_code,
				'o_product_id'		=>	$request['product_id'],
				'o_product_type'	=>  $request['product_type'],
				'o_shipping_service' => NULL,
				'o_currency'		=> $request['currency'],
				'o_sub_total'		=> $request['purchase_val'],
				'o_total'			=> $request['purchase_val'], #changes when delivery added
				'o_quantity'		=> $request['quantity'],
				'o_purchased_for'	=> $request['purchase_val'],
				'o_delivery_charge' => $deliverycharge,
				'o_delivery_provider' =>$delivery_provider,
				'o_dispatched'		=>	0,
				'o_dispatched_date' =>	NULL,
				'o_returned' 		=>	0,
				'o_returned_date' 	=>	NULL,
				'o_cancelled' 		=>	0,
				'o_cancelled_date' 	=>	NULL,
				'o_tracking_no' 	=>	NULL,
				'o_tracking_link' 	=>	NULL,
				'o_collection_time' =>	$o_collection_time,
				'o_lend_subscribe_starts' =>  NULL,
				'o_lend_subscribe_ends'   =>  NULL,
                'reminder_time'=>$reminder_time,
				'created_at'=>$date,
		        'updated_at'=>$date,
			];

			$order_generated = DB::table('orders')->insertGetId($insert_arr);

			if(!empty($order_generated)){
				#insert into credit details
				/*$credit_detail_id = Credit_detail::create([
					'user_id'	=>$formDetails['seller_id'],
					'order_id'	=>$order_generated,
					'value'		=>$formDetails['purchase_val'],
					'added_on'	=>date('Y-m-d h:i:s'),
					'created_at'=>date('Y-m-d h:i:s'),
		        	'updated_at'=>date('Y-m-d h:i:s'),
				])->id;*/
				# Insert into vat table
				if($request['currency']=='GBP' || $request['currency']=='EUR')
				{
					$vatamount = $request['purchase_val'];
					$fee_net = $vatamount * 0.15;
					$fee_gross = $fee_net / 1.2;
					$fee_vat = $fee_net - $fee_gross;
				}
				else
				{
					$vatamount = $request['purchase_val'];
					$fee_gross = $request['purchase_val'];
					$fee_vat = '0';
					$fee_net = $request['purchase_val'];
				}

				vat::create(['order_id'=>$order_generated,
								'amount'=>$vatamount,
								'fee_gross'=>$fee_gross,
								'fee_vat'=>$fee_vat,
								'fee_net'=>$fee_net,
								'created_at'=>$date,
								'updated_at'=>$date]);

				$details_arr = [
					'user_id'	=>$request['seller_id'],
					'order_id'	=>$order_generated,
					'value'		=>$request['purchase_val'],
					'added_on'	=>$date,
                    'active_on' =>$activedate,
					'created_at'=>$date,
		        	'updated_at'=>$date
				];
				$credit_detail_id = DB::table('credit_details')->insert($details_arr);
				#insert into credit details histories
				$history_arr = [
					'user_id'	=>$request['seller_id'],
					'order_id'	=>$order_generated,
					'amount'	=>$request['purchase_val'],
					'comment'	=>1,
					'currency'  =>$request['currency'],
					'created_at'=>$date,
		        	'updated_at'=>$date
				];
				$credit_hostory_id = DB::table('credit_histories')->insert($history_arr);
				if(empty($credit_detail_id) || empty($credit_hostory_id)){
					$response['error'] = 12;
					return $response;
				}else{
					#managing inventory
					$old_quantity = Product::where('id',$request['product_id'])->value('p_quantity');
					$new_quantity = $old_quantity-$request['quantity'];
					if($new_quantity < 0){
						$new_quantity = 0;
					}else{
						$new_quantity = $new_quantity;
					}

					#update new quantity
					$update_q = DB::table('products')->where('id',$request['product_id'])->update(['p_quantity'=>$new_quantity]);
					$order_generated = base64_encode($order_generated);
					$response['success'] = 1;
					$response['order_id'] = $order_generated;
					return $response;
				}

			}else{
				$response['error'] = 13;
				return $response;

			}
		}catch(\Exception $e){
			$response['error'] = $e->getMessage();
			return $response;
		}
	}
	public function paywithcredit(Request $request)
    {
        //dd($request['buy_product_option']);
        //print_r($formDetails);die;
        $date = $this->summertime();
        //$activedate = '';
        $creditcleardate = system_setting::where('status','1')->get();
        $reminder_time = $request['remindertime'];
        //dd($request['p_type']);
        if($request['product_type']=='1' || $request['product_type']=='3') {
            $activedate = date('Y-m-d H:i:s', strtotime($date . '+ ' . $creditcleardate[0]['clear_credit_period'] . ' days'));
        }
        if($request['product_type']=='2')
        {
            $activedate = date('Y-m-d H:i:s', strtotime($date . '+ ' . $creditcleardate[0]['clear_credit_period_service'] . ' days'));
        }
        if($request['product_type']=='3')
        {
            $o_subs_period = $request['o_subs_period'];
            $p_price_per_optn = $request['p_price_per_optn'];
            $subs_status = '1';
        }
        else
        {
           $o_subs_period = '0';
           $p_price_per_optn = '0';
           $subs_status = '0';
        }
        try{
            $order_id = mt_rand(10000000,99999999);
            $userId = Auth::id();
            $o_name= Auth::user()->name;
            $o_email = Auth::user()->email;
            $o_address = Auth::user()->street_address1." ".Auth::user()->street_address1;
            $o_country = Auth::user()->country;
            $o_postal_code = Auth::user()->pincode;
            if(isset($request['slot_time']) && !isset($request['service_slot_time']))
            {
                $collection_time = $request['slot_time'];
            }
            elseif(isset($request['service_slot_time']) && !isset($request['slot_time']))
            {
                $collection_time = implode(',',$request['service_slot_time']);
            }
            else
            {
                $collection_time = '';
            }

            if(!empty($collection_time)){

                if(isset($request['slot_time']))
                {
                    $coll_data = explode('|', $collection_time);
                    $coll_date = $coll_data[0];
                    $coll_time = $coll_data[1];

                    $time_arr = explode('-', $coll_time);
                    $start_time = date("H:i", strtotime($time_arr[0]));
                    $end_time = date("H:i", strtotime($time_arr[1]));

                    $o_collection_time = $coll_date.''.$start_time.' - '.$coll_date.''.$end_time;
                    #current time : date('Y-m-d H:i')
                }
                else
                {
                    $collection = explode(',',$collection_time);
                    $o_collection_time_array;
                    $length = count($collection);
                    $o_collection_time = '';
                    for($i=0;$i<$length;$i++)
                    {
                        $coll_data = explode('|', $collection[$i]);

                        $coll_date = $coll_data[0];
                        $coll_time = $coll_data[1];
                        $time_arr = explode('-', $coll_time);
                        $start_time = date("H:i", strtotime($time_arr[0]));
                        $end_time = date("H:i", strtotime($time_arr[1]));
                        $o_collection_time_array[$i] = $coll_date.''.$start_time.' - '.$coll_date.''.$end_time;
                        if($i==$length)
                        {

                        }

                    }
                    $o_collection_time = implode(',',$o_collection_time_array);
                    //$o_collection_time = implode(',',$o_collection_time_array);
                    #current time : date('Y-m-d H:i')
                }
            }else{
                $o_collection_time = NULL;
            }

            $deliverycharge = '0';
            $delivery_provider = '0';
            $insert_arr = [
                'order_id'			=> 	$order_id,
                'seller_id' 		=>	$request['seller_id'],
                'user_id'			=>	$userId,
                'braintree_id'		=>	'',
                'o_name'			=>	$o_name,
                'o_email'			=>	$o_email,
                'o_address' 		=>	$o_address,
                'o_country'			=>	$o_country,
                'o_postal_code'		=>	$o_postal_code,
                'o_product_id'		=>	$request['product_id'],
                'o_product_type'	=>  $request['product_type'],
                'o_shipping_service' => NULL,
                'o_currency'		=> $request['currency'],
                'o_sub_total'		=> $request['purchase_val'],
                'o_total'			=> $request['purchase_val'], #changes when delivery added
                'o_quantity'		=> $request['quantity'],
                'o_purchased_for'	=> $request['purchase_val'],
                'o_delivery_charge' => $deliverycharge,
                'o_delivery_provider' =>$delivery_provider,
                'o_dispatched'		=>	0,
                'o_dispatched_date' =>	NULL,
                'o_returned' 		=>	0,
                'o_returned_date' 	=>	NULL,
                'o_cancelled' 		=>	0,
                'o_cancelled_date' 	=>	NULL,
                'o_tracking_no' 	=>	NULL,
                'o_tracking_link' 	=>	NULL,
                'o_collection_time' =>	$o_collection_time,
                'o_lend_subscribe_starts' =>  NULL,
                'o_lend_subscribe_ends'   =>  NULL,
                'reminder_time'=>$reminder_time,
                'o_subs_period'=>$o_subs_period,
                'p_price_per_optn'=>$p_price_per_optn,
                'subs_status'=>$subs_status,
                'created_at'=>$date,
                'updated_at'=>$date,
            ];

            $order_generated = DB::table('orders')->insertGetId($insert_arr);

            $systemsetting = system_setting::where('status','1')->get();

            $discount = $systemsetting[0]['credit_discount'];
            $discountamount = ($discount/100) * $request['purchase_val'];
            $updatedamount = $request['purchase_val']-$discountamount;
            $updatedamount = $updatedamount + $deliverycharge;

            $walletupdate = User::where('id',$userId)->decrement('wallet',$updatedamount);

            if(!empty($order_generated)){
                #insert into credit details
                /*$credit_detail_id = Credit_detail::create([
                    'user_id'	=>$formDetails['seller_id'],
                    'order_id'	=>$order_generated,
                    'value'		=>$formDetails['purchase_val'],
                    'added_on'	=>date('Y-m-d h:i:s'),
                    'created_at'=>date('Y-m-d h:i:s'),
                    'updated_at'=>date('Y-m-d h:i:s'),
                ])->id;*/
                # Insert into vat table
                if($request['currency']=='GBP' || $request['currency']=='EUR')
                {
                    $vatamount = $request['purchase_val'];
                    $fee_net = $vatamount * 0.15;
                    $fee_gross = $fee_net / 1.2;
                    $fee_vat = $fee_net - $fee_gross;
                }
                else
                {
                    $vatamount = $request['purchase_val'];
                    $fee_gross = $request['purchase_val'];
                    $fee_vat = '0';
                    $fee_net = $request['purchase_val'];
                }

                vat::create(['order_id'=>$order_generated,
                    'amount'=>$vatamount,
                    'fee_gross'=>$fee_gross,
                    'fee_vat'=>$fee_vat,
                    'fee_net'=>$fee_net,
                    'created_at'=>$date,
                    'updated_at'=>$date]);
                $gbpamount = $request['purchase_val'] / $request['currency_rate'];
                $details_arr = [
                    'user_id'	=>$request['seller_id'],
                    'order_id'	=>$order_generated,
                    'currency'  =>$request['currency'],
                    'def_gbp_amount'=>$gbpamount,
                    'value'		=>$request['purchase_val'],
                    'added_on'	=>$date,
                    'active_on' =>$activedate,
                    'created_at'=>$date,
                    'updated_at'=>$date
                ];
                $credit_detail_id = DB::table('credit_details')->insert($details_arr);
                #insert into credit details histories
                $history_arr = [
                    'user_id'	=>$request['seller_id'],
                    'order_id'	=>$order_generated,
                    'amount'	=>$request['purchase_val'],
                    'comment'	=>1,
                    'currency'  =>$request['currency'],
                    'created_at'=>$date,
                    'updated_at'=>$date
                ];
                $credit_hostory_id = DB::table('credit_histories')->insert($history_arr);
                if(empty($credit_detail_id) || empty($credit_hostory_id)){
                    $response['error'] = 12;
                    return $response;
                }else{
                    #managing inventory
                    $old_quantity = Product::where('id',$request['product_id'])->value('p_quantity');
                    $new_quantity = $old_quantity-$request['quantity'];
                    if($new_quantity < 0){
                        $new_quantity = 0;
                    }else{
                        $new_quantity = $new_quantity;
                    }

                    #update new quantity
                    $update_q = DB::table('products')->where('id',$request['product_id'])->update(['p_quantity'=>$new_quantity]);
                    $order_generated = base64_encode($order_generated);
                    $response['success'] = 1;
                    $response['order_id'] = $order_generated;
                    $response['discount'] = base64_encode($discountamount);
                    return $response;
                }

            }else{
                $response['error'] = 13;
                return $response;

            }
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return $response;
        }
    }
}
