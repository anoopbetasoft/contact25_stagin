<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Input;
use DB;
use Redirect;
use App\User;
use App\Role;

class AdminController extends Controller
{
    	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware(['auth', 'verified','checkAdminRole']);
	}
	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index()
	{
		$userId = Auth::id();
		#get users' detail
		$uDetails = User::where('id',$userId)->get();
		return view("admin.home", compact("uDetails","userId"));
	}

	/*
	customers listing page
	*/

	public function customer_listing()
	{
		$userId = Auth::id();
		$uDetails = User::where('id',$userId)->get();

		#get list of all the users to impersonate
		$customers = User::where('id', '!=', $userId)->get();


		return view("admin.customers", compact("uDetails","userId","customers"));
	}
	/*
	Impersonate
	*/
	public function impersonate( $user_id )
	{  
        if( $user_id != ''){
            $user = User::find($user_id);
            //echo"<pre>";print_r($user);die;
            Auth::user()->impersonate($user);
            return redirect('/');
        }
        return redirect()->back();
	}

	/*
	profile settings
	*/
	public function account_setting()
	{

		$userId = Auth::id();
		
		#get users' detail
		$uDetails = User::where('id',$userId)->get();
		return view('admin.account_setting', compact("uDetails","userId"));
	}

	/*
	save 2 auth value
	*/

	public function admin_two_auth(Request $request)
	{	
		


		$userId = Auth::id();
		$Existingcode = Auth::user()->contact_code;
		$Existingcontact = Auth::user()->contact_no;

		//print_r($request->all());die;

		if(!isset($request['auth_val'])){
			#some error 
			$response['success'] = 0;
            $response['message'] = "Auth Value not found ";
            return response()->json($response);
		}

		#disable 2 auth
		if($request['auth_val'] == 0) {
			$check_status = User::where('id', $userId)->update(['two_way_auth' => 0]);
			if(!empty($check_status)){
				#some error 
				$response['success'] = 1;
	            $response['message'] = "Settings Updated.";
	            return response()->json($response);
			}
		}	

		#enable 2 auth and verify the mobile no.
		if($request['auth_val'] == 1) {

			#check the mobile no, if same dont ask for otp
			if(isset($request['contact_no_hidden']) && !empty($request['contact_no_hidden'])){
				$userno = trim($request['contact_no_hidden']);
			}else{
				$userno = trim($request['contact_mobile']);
			}
			$contact_no = trim($request['dial_code']).''.$userno;
			$existing_no = $Existingcode.''.$Existingcontact;

			
			if($contact_no == $existing_no){

				#same numbers
				$check_status = User::where('id', $userId)->update(['two_way_auth' => 1, 'contact_country'=>$request['contact_country']]);

				if(!empty($check_status)){
					#some error 
					$response['success'] = 1;
		            $response['message'] = "Settings Updated.";
		            return response()->json($response);
				}

			}else{
				#different no, generate sms
				#send otp to user
                $digits = 4;
                $otp_no = rand(pow(10, $digits-1), pow(10, $digits)-1);
                
                #send
                $user = new User();
                $user->sendText($contact_no,"{{config('app.name')}}","Dear user, your OTP is : ".$otp_no." . Use this password update your mobile number.");
                $response['success'] = 2;
                $response['message'] = "OTP has been sent to your mobile.";
                $response['otpval'] = Hash::make($otp_no).'_tval_'.date('Y-m-d h:i:s');
                $response['contact_no_hidden'] = $userno;
                //$response['original_otp'] = $otp_no;
                return response()->json($response);
			}
		}
	} #admin_two_auth

	/*
	save 2 auth value
	*/

	public function admin_two_auth_otp(Request $request)
	{


		$userId = Auth::id();
		if(empty($request['otp']) || empty($request['otp_val'])){
            $response['success'] = 0;
            $response['message'] = "Please enter the OTP";
            return response()->json($response);
        }else{
        	#get time of otp sent
            $input = "_tval_";
            $timeOTP_sent = explode('_tval_', $request['otp_val']);
            $hash_otp = $timeOTP_sent[0];
            $otp_time_sent = $timeOTP_sent[1];

            #get current time
            $t=time();
            $current_time = date("Y-m-d h:i:s",$t);

            #get time different
            $ts1 = strtotime($otp_time_sent);
            $ts2 = strtotime($current_time);     
            $minutes_diff = ($ts2 - $ts1) / 60; 

            if($minutes_diff > 5){ # 5 minutes limit

                $response['success'] = 3;
                $response['message'] = "OTP has expired. Please resend the otp";
                return response()->json($response);
            }
            if (Hash::check($request['otp'], $hash_otp)) {
            	#update user
            	$update_arr = [
            		"contact_code"=>$request['dial_code'],
            		"contact_no"=>$request['contact_no_hidden'],
            		"two_way_auth"=>1,
            		"contact_country"=>$request['contact_country'],
            	];

            	$update_status = DB::table('users')->where('id', $userId)->update($update_arr);
                
            	if(!empty($update_status)){
            		$response['success'] = 1;
            		$response['message'] = "Settings Updated.";
                	return response()->json($response);
            	}else{
            		$response['success'] = 0;
	                $response['message'] = "Some error occured.";
	                return response()->json($response);
            	}
            }else{
                $response['success'] = 0;
                $response['message'] = "OTP does not matches";
                return response()->json($response);
            }
        }
	}#admin_two_auth_otp
}
