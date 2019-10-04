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


class PaymentsController extends Controller
{
    //
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	
	public function __construct()
	{
		$this->middleware(['auth', 'verified','checkLoginRole']);
	}


	/*
	Payment process : product page
	*/
    public function process(Request $request)
	{

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
				die;
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

			    $amount = $request['amount'];

			    $nonce = $payload['nonce'];

			    $method_result = Braintree_PaymentMethod::create([
				    'customerId' => $customer_id,
				    'paymentMethodNonce' => $nonce
				]);	
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
					    		'created_at'				=> date('Y-m-d h:i:s'),
				       			'updated_at'				=> date('Y-m-d h:i:s'),
					    	];
					    	
							$check_transition = DB::table('user_cards')->insert($insert_arr);
							if(empty($check_transition)){

								$response['error'] = 111;
								return response()->json($response);
								
							}else{

								return response()->json($this->saveOrder($formDetails, $customer->customer->id));
							}
				    	}else{

				    		return response()->json($this->saveOrder($formDetails, $request['braintree_id']));

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
	public function saveOrder($formDetails, $braintree_id)
	{

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
				'o_total'			=> $formDetails['purchase_val'], #changes when delivery added
				'o_quantity'		=> $formDetails['quantity'],
				'o_purchased_for'	=> $formDetails['purchase_val'],
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
				'created_at'=>date('Y-m-d h:i:s'),
		        'updated_at'=>date('Y-m-d h:i:s'),
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
				$details_arr = [
					'user_id'	=>$formDetails['seller_id'],
					'order_id'	=>$order_generated,
					'value'		=>$formDetails['purchase_val'],
					'added_on'	=>date('Y-m-d h:i:s'),
					'created_at'=>date('Y-m-d h:i:s'),
		        	'updated_at'=>date('Y-m-d h:i:s'),
				];
				$credit_detail_id = DB::table('credit_details')->insert($details_arr);
				#insert into credit details histories
				$history_arr = [
					'user_id'	=>$formDetails['seller_id'],
					'order_id'	=>$order_generated,
					'amount'	=>$formDetails['purchase_val'],
					'comment'	=>1,
					'currency'  =>$formDetails['currency'],
					'created_at'=>date('Y-m-d h:i:s'),
		        	'updated_at'=>date('Y-m-d h:i:s'),
				];
				$credit_hostory_id = DB::table('credit_histories')->insert($history_arr);
				if(empty($credit_detail_id) || empty($credit_hostory_id)){
					$response['error'] = 12;
					return $response;
				}else{
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
}
