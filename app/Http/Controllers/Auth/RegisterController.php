<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    /*public function register(Request $request)
    {
        $data = $request->all();
        $validator = $this->validator($data);

        if ($validator->fails()) {
            $response['error'] = $validator->errors();
            return response()->json($response);
        }

        $this->create($data);
        $response['success'] = 200;


        return response()->json($response);
    }*/

    // public function sendOtp(Request $request)
    // {
    //     $data = $request->all();
    //     $validator = $this->validator($data);

    //     if ($validator->fails()) {
    //         $response['error'] = $validator->errors();
    //         return response()->json($response);
    //     }


    //     //dd($_POST);
    //     $user= new User;
    //     $response = $user->sendsms('7968150172','Contact25','ABC');
    //     dd($response);
    //     $response['success'] = 200;


    //     return response()->json($response);
    // }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'   => ['required', 'string', 'min:6', 'confirmed'],
            'contact_no' => ['required', 'string'],
        ]);

        return $validator;
    }

    /*
    Sending otp on register
    */
    protected function register_otp(Request $request){



        $data = $request->all();
        $validator = $this->validator($data);

        if ($validator->fails()) {
            $response['error'] = $validator->errors();
            return response()->json($response);
        }
        else{

            if(!empty(trim($request['contact_no']))){
                $digits = 4;
                $otp_no = rand(pow(10, $digits-1), pow(10, $digits)-1);

                $fullContact = $request['dial_code'].''.$request['contact_no'];
                $user = new User();
                $user->sendText($fullContact,"Contact25","Your Contact25 activation code is : ".$otp_no." . Use this code to validate your registration. This code will expire in 5 minutes.");
                $response['success'] = 2;
                $response['message'] = "Activation Code has been sent to your mobile.";
                $response['otpval'] = Hash::make($otp_no).'_tval_'.date('Y-m-d h:i:s');
                //$response['original_otp'] = $otp_no;
                return response()->json($response);

            }else{
                $response['success'] = 0;
                $response['message'] = "Please enter a valid mobile no.";
                return response()->json($response);
            }
        }
    } #sending otp


    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function register(Request $request)
    {
        $data = $request->all();
        $validator = $this->validator($data);

        if ($validator->fails()) {
        $response['error'] = $validator->errors();
            return response()->json($response);
        }else{
            if(!empty(trim($request['contact_no'])) && !empty($request['otp'])){

                #get time of otp sent
                $input = "_tval_";
                $timeOTP_sent = explode('_tval_', $request['otpval']);
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
                    $response['message'] = "Activation Code has expired. Please resend the code";
                    return response()->json($response);
                }

               /* if (Hash::check($request['otp'], $hash_otp)) {*/

                    $role_id = Role::where('display_name','=', 'customer')->first(['id']);
                    User::create([
                        'role_id'    => $role_id->id,
                        'facebook_id'=> NULL,
                        'google_id'  => NULL,
                        'name'       => $request['name'],
                        'email'      => $request['email'],
                        'contact_code'=>$request['dial_code'],
                        'contact_no' => $request['contact_no'],
                        'contact_country'=>$request['contact_country'],
                        'password'   => Hash::make($request['password']),
                        'contact_verified_at'=> date('Y-m-d h:i:s'),
                        'contact_verify_status'=>1,
                        'lat'=>'',
                        'lng'=>'',
                        'location'=>'',
                        'timezone'=>'',
                        'return_address'=>''
                    ]);
                    $response['success'] = 200;
                    return response()->json($response);
                /*}else{
                    $response['success'] = 0;
                    $response['message'] = "Please enter correct activation code";
                    return response()->json($response);
                }*/
            }else{
                $response['success'] = 0;
                $response['message'] = "Please enter the activation";
                return response()->json($response);
            }
        }
    } #register ends here

}
