@extends('layouts.include_login')

@section('content')
    <section id="wrapper">
        <div class="login-register" style="background-image:url(assets/images/background/login-register.jpg);">
            <div class="login-box card">
                <div class="card-body">
                    <div class="form-horizontal form-material"  id="login_form" >
                        
                        <h3 class="text-center m-b-20"><img src="{{('admin-login/images/logo-icon.png')}}" alt="Dashboard UI Kit"><br>
                        <img src="{{('admin-login/images/logo-text.png')}}" alt="Dashboard UI Kit">
                        </h3>
                            @if (\Session::has('msg'))
                            <div class="alert alert-danger">
                                <ul>
                                    <li>{{\Session::get('msg')[0]}}</li>
                                </ul>
                            </div>
                            @endif
                            <form id="loginForm" autocomplete="off">
                                @csrf
                                <input type="hidden" name="login_otp_val" value="">
                                <div class="alert alert-danger LoginError" style="display:none"></div>
                                <div class="alert alert-success LoginSuccess" style="display:none"></div>
                                <div class="login_form_div">
                                    <div class="form-group ">
                                        <div class="col-xs-12">
                                            <input class="form-control c-input" type="email" name="email" id="input1" placeholder="clark@dashboard.com">
                                            <span id="login_email"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <input class="form-control" type="password" name="password"  id="input2" required="" placeholder="Numbers, Letters..."> </div>
                                            <span id="login_password"></span>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <div class="d-flex no-block align-items-center">
                                                <!--<div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                    <label class="custom-control-label" for="customCheck1">Remember me</label>
                                                </div> -->
                                                <div class="ml-auto">
                                                    <a id="to-recover"  onclick="show('forgot_form')" class="text-muted"><i class="fas fa-lock m-r-5" ></i> Forgot pwd?</a> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group text-center">
                                        <div class="col-xs-12 p-b-20">
                                            <button class="btn btn-block btn-lg btn-info btn-rounded" type="submit"  id="login-button" >Log In</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="login_otp_div" style="display:none">
                                    <div class="form-group ">
                                        <label>Please enter the Login Code</label>
                                        <input class="form-control c-input" type="text" name="login_otp" id="input10" placeholder="Login Code">
                                        <span id="login_otp"></span>
                                        
                                    </div>
                                    <div class="form-group text-center">
                                    <div class="col-xs-12 p-b-20">
                                        <button class="btn btn-block btn-lg btn-info btn-rounded" type="button"  id="login-otp" >SUBMIT</button>
                                    </div>
                                    </div>
                                </div>
                                <div class="form-group text-center m-t-20">
                                </div>
                                
                                <button class="btn btn-block btn-lg btn-danger btn-rounded" type="button" style="display: none;" id="login-otp-resend" >RE-SEND Login Code</button>
                                
                            </form>
                        

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">
                                <div class="social">
                                    <a class="btn  btn-facebook" data-toggle="tooltip" title="Login with Facebook" href="{{url('/redirect')}}/facebook"><i aria-hidden="true" class="fab fa-facebook-f"></i> </a>

                                    <a class="btn btn-googleplus" data-toggle="tooltip" title="Login with Google" href="{{url('/redirect')}}/google"><i aria-hidden="true" class="fab fa-google-plus-g"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-b-0" id="registerdiv_link">
                            <div class="col-sm-12 text-center">
                                Don't have an account? <a class="text-info m-l-5"  onclick="show('register_form')"><b>Sign Up</b></a>
                            </div>
                        </div>
                    </div>

                    <div class="form-horizontal" id="forgot_form" style="display: none;">
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <h3>Recover Password</h3>
                                <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
                                <div class="forgot_mail_status" role="alert" style="display:none">  
                                </div>
                            </div>
                        </div>
                        <form class="c-card__body" id="forgotEmailForm" autocomplete="off">
                            @csrf
                            <div class="form-group ">
                                <div class="col-xs-12">
                                    <input class="form-control" type="email"  name="email" required="" id="input8" placeholder="Email"> </div>
                            </div>
                            <div class="form-group text-center m-t-20">
                                <div class="col-xs-12">
                                    <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" id="forgot_button">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="register_form" style="display: none;">
                       <h3 class="text-center m-b-20"><img src="{{('admin-login/images/logo-icon.png')}}" alt="Dashboard UI Kit"><br>

                        <img src="{{('admin-login/images/logo-text-large.png')}}" alt="Dashboard UI Kit"> 
                        </h3>
                        <div class="c-card u-mb-xsmall">
                            <form class="c-card__body" id="registerForm" autocomplete="off">
                                <div class="alert alert-danger signUpError" style="display:none"></div>
                                <div class="alert alert-success signUpSuccess" style="display:none"></div>
                                <input type="hidden" name="otpval" value="">
                                @csrf
                                <div class="first_register">
                                    <div class="form-group">
                                    
                                    <input class="form-control" type="text" placeholder="Name" name="name" id="input3">
                                    <p id="name"></p>
                                    </div>

                                    <div class="form-group">
                                        
                                        <input class="form-control" type="email" name="email" id="input4" placeholder="clark@dashboard.com">
                                        <p id="email"></p>
                                    </div>

                                    <div class="form-group">
                                        <label class="c-field__label" for="input5">Contact No</label>
                                        <input class="form-control c-input" type="text" name="contact_no" id="phone" id="input5"
                                        >
                                        <p id="contact_no"></p>
                                    </div>

                                    <div class="form-group">
                                        
                                        <input class="form-control" type="password" name="password" id="input6"
                                               placeholder="Numbers, Letters...">
                                        <p id="password"></p>
                                    </div>

                                    <div class="form-group">
                                        
                                        <input class="form-control" type="password" id="input7" name="password_confirmation"
                                               placeholder="Confirm Password">
                                    </div>

                                    <button class="btn btn-block btn-lg btn-info btn-rounded" type="submit" id="signUp"> SIGN UP</button>
                                </div>
                                <div class="otp_register" style="display: none">
                                    <div class="form-group">
                                        <input class="form-control" type="text" placeholder="Activation Code" name="otp" id="input9">
                                        <p id="otp"></p>
                                    </div>
                                    
                                    <button class="btn btn-block btn-lg btn-info btn-rounded" type="submit" id="sendOtp">
                                        SUBMIT
                                    </button>
                                </div>
                                <div class="form-group text-center m-t-20">
                                </div>
                                <button class="btn btn-block btn-lg btn-danger btn-rounded" type="button" id="resendOtp" style="display: none">
                                RE-SEND Activation code
                                </button>

                            </form>
                        </div>
                        <div class="o-line" id="show_login_form">
                            <a class="u-text-mute u-text-small" onclick="show('login_form')">Already have an account?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection

