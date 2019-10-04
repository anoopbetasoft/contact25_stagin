<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;


use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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


    public function forgotEmail(Request $request)
    {   

        
        $credentials = ['email' => trim($request->email)];

        $response = Password::sendResetLink($credentials, function (Message $message) {
            $message->subject($this->getEmailSubject());
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return \Response::json([
                    'status' => 1,
                    'message' => 'We have e-mailed your password reset link!'
                ], 201);
                //return redirect()->back()->with('status', trans($response));
            case Password::INVALID_USER:
                return \Response::json([
                    'status' => 0,
                    'message' => 'The email id does not exists.'
                ], 201);
                //return redirect()->back()->withErrors(['email' => trans($response)]);
        }
    }
}
