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
use App\system_setting;
use App\return_setting;
use Session;

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
	 * System Setting
	 */
    public function system_setting(Request $request)
    {

        if($request['inpost_amount'])
        {
            $inpost_amount = $request['inpost_amount'];
            $clear_credit_period = $request['clear_credit_period'];
            $credit_discount = $request['credit_discount'];
            $clear_credit_period_service = $request['clear_credit_period_service'];
            $remind_time = $request['remind_time'];
            $product_not_delivered_limit = $request['product_not_delivered_limit'];
            $product_cancel_limit_seller = $request['product_cancel_limit_seller'];
            $no_of_day_for_claim = $request['no_of_day_for_claim'];
            system_setting::where('status','1')->update(['status'=>'0']);
            system_setting::create(['inpost_amount'=>$inpost_amount,'clear_credit_period'=>$clear_credit_period,'credit_discount'=>$credit_discount,'remind_time'=>$remind_time,'clear_credit_period_service'=>$clear_credit_period_service,'status'=>'1','product_cancel_limit_seller'=>$product_cancel_limit_seller,'product_not_delivered_limit'=>$product_not_delivered_limit,'no_of_day_for_claim'=>$no_of_day_for_claim,'created_at'=>date('Y-m-d H:i:s')]);
            Session::flash('message', 'New Policy Created Successfully');
            return back();
        }
        if($request['inpost_return_amount'])
        {
            $days_allowed_for_refund = $request['days_allowed_for_refund'];
            $credit_limit_refund = $request['credit_limit_refund'];
            $inpost_return_amount = $request['inpost_return_amount'];
            $days_allowed_for_return_label = $request['days_allowed_for_return_label'];
            $product_returning_limit = $request['product_returning_limit'];
            return_setting::where('status','1')->update(['status'=>'0']);
            return_setting::create(['days_allowed_for_refund'=>$days_allowed_for_refund,'credit_limit_refund'=>$credit_limit_refund,'inpost_return_amount'=>$inpost_return_amount,'days_allowed_for_return_label'=>$days_allowed_for_return_label,'status'=>'1','product_returning_limit'=>$product_returning_limit,'created_at'=>date('Y-m-d H:i:s')]);
            Session::flash('message', 'New Policy Created Successfully');
            return back();
        }
        else {
            $userId = Auth::id();

            #get users' detail
            $uDetails = User::where('id', $userId)->get();
            $systemsettting = system_setting::where('status','1')->get();
            $oldpolicy = system_setting::where('status','0')->get();
            $returnsetting = return_setting::where('status','1')->get();
            $oldreturnpolicy = return_setting::where('status','0')->get();
            return view('admin.system_setting', compact("uDetails", "userId", "systemsettting","oldpolicy","returnsetting","oldreturnpolicy"));
        }

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
