<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Input;
use Redirect;
use Socialite;
use App\User;
use App\Role;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    #login
    public function login(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            $response['error'] = $validator->errors();
            return response()->json($response);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            #steps here
            /*
            1. check if 2 auth enabled
            2. if enabled > send sms to user
            3. send and receive sms
            4. redirect user as per the requirement
            */
            if (Hash::check($request->get('password'), $user->password)) {
                if($user['two_way_auth'] == 1){ #2way auth enabled
                    #send otp to user
                    $digits = 4;
                    $otp_no = rand(pow(10, $digits-1), pow(10, $digits)-1);

                    #get user's registerd mobile no.
                    $fullContact = $user['contact_code'].''.$user['contact_no'];

                    #send
                    $user = new User();
                    $user->sendText($fullContact,"Contact25","Your Contact25 login code is : ".$otp_no." . Use this code to login into your account. This will expire in 5 minutes.");
                    $response['success'] = 2;
                    $response['message'] = "Login code has been sent to your mobile.";
                    $response['otpval'] = Hash::make($otp_no).'_tval_'.date('Y-m-d h:i:s');
                    $response['original_otp'] = $otp_no;
                }
                elseif($user['active_status']=='0')
                {
                    $response['error'] = ['password' => 'Your Account is suspended'];
                }
                else{
                    auth()->login($user);
                    $response['success'] = 200;
                }
            }else{
                $response['error'] = ['password' => 'Password not match!'];
            }
        }else{
            $response['error'] = ['email' => 'User with these credentials not found!'];
        }
        return response()->json($response);
    } #login ends here
    /*
    login after otp
    */
    public function login_otp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            $response['error'] = $validator->errors();
            return response()->json($response);
        }
        if(empty($request['login_otp'])){
            $response['success'] = 0;
            $response['message'] = "Please enter the Login code";
            return response()->json($response);
        }else{
            $user = User::where('email', $request['email'])->first();
            if ($user) {
                if (Hash::check($request->get('password'), $user->password)) {
                    
                    #get time of otp sent
                    $input = "_tval_";
                    $timeOTP_sent = explode('_tval_', $request['login_otp_val']);
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
                        $response['message'] = "Login code has expired. Please resend the login code";
                        return response()->json($response);
                    }
                    if (Hash::check($request['login_otp'], $hash_otp)) {
                        auth()->login($user);
                        $response['success'] = 200;
                        return response()->json($response);
                    }else{
                        $response['success'] = 4;
                        $response['message'] = "Login code does not matches";
                        return response()->json($response);
                    }
                }
            }
        }
    }#otp login ends here

    #social login
    public function redirectToProvider($provider)
    {
       return Socialite::driver($provider)->redirect();
    }

    #social login
    public function handleProviderCallback($provider)
    {
        #get role id
        $role_id = Role::where('display_name','=', 'customer')->first(['id']);

        try {
            $user = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('msg', ['Please try again. Social provider not available']);
        }

        //echo "<pre>";print_r( $user);die;
        
        if(empty($user->email)){
            return redirect('/login')->with('msg', ['Please verify your email id with facebook']);   
        }

        #check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();
        if($existingUser){
            #log them in
            auth()->login($existingUser, true);
        } else {
            
            if($provider == 'google'){
                $newUser                  = new User;
                $newUser->name              = $user->name;
                $newUser->email             = $user->email;
                $newUser->role_id           = $role_id->id;
                $newUser->google_id         = $user->id;
                $newUser->facebook_id       = NULL;
                $newUser->contact_no        = NULL;
                $newUser->email_verified_at = date('Y-m-d h:i:s');
                $newUser->avatar            = $user->avatar;
                $newUser->avatar_type       = 2;
                $newUser->lat               = '';
                $newUser->lng               = '';
                $newUser->location          = '';
                $newUser->timezone          = '';
                $newUser->return_address    = '';
                $newUser->save();
                auth()->login($newUser, true);
            }elseif($provider == 'facebook'){
                $newUser                    = new User;
                $newUser->name              = $user->name;
                $newUser->email             = $user->email;
                $newUser->role_id           = $role_id->id;
                $newUser->google_id         = NULL;
                $newUser->facebook_id       = $user->id;
                $newUser->contact_no        = NULL;
                $newUser->email_verified_at = date('Y-m-d h:i:s');
                $newUser->avatar            = $user->avatar;
                $newUser->avatar_type       = 2;
                $newUser->lat               = '';
                $newUser->lng               = '';
                $newUser->location          = '';
                $newUser->timezone          = '';
                $newUser->return_address    = '';
                $newUser->save();
                auth()->login($newUser, true);
            }
            
        }
        return redirect()->to('/dashboard');
    }

    public function logout(Request $request)
    {
        //die('sfd');
       // $this->guard('web_buyer')->logout();
        Auth::logout();
        return redirect('login');
    }

}
