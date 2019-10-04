
<form id="update_auth">
    @csrf
    <input type="hidden" name="otp_val" value="">
    <input type="hidden" name="contact_no_hidden" value="">
    <div class="c-alert c-alert--danger auth_danger" style="display: none"></div>
    <div class="c-alert c-alert--success auth_success" style="display: none"></div>

    <div class="before_otp">
        <div class="row" >
            
            <div class="col-lg-5">
                <div class="c-field u-mb-small">
                    <label class="c-field__label" for="firstNameOther"> 
                        2 step authentication 
                        <span id="2auth_label"> <b>{{$twoauth_val}}</b> </span>
                    </label> 
                    <input type="checkbox" id="2_auth" {{$check_val}} class="c-input newjsswicth" name="auth_val"  />
                </div>
            </div>
            <div class="col-lg-5 2auth_form" style="{{$style_val}}" >
                <div class="c-field u-mb-small " >
                    <label class="c-field__label" for="lastNameOther">
                        Contact Number
                    </label> 
                    <input class="c-input" name="contact_mobile" type="number" id="phone_2auth"  placeholder="telephone number" value="{{$uDetails[0]['contact_no']}}">
                </div>
            </div>
        </div>

        <div class="row">
             <div class="col-lg-5">
                <button type="submit" class="c-btn c-btn--info" id="update_auth_button">Update</button>
            </div>
        </div>
    </div>

    <div class="after_otp" style="display: none">
        <div class="row" >
            <div class="col-lg-5">
                <div class="c-field u-mb-small">
                    <label class="c-field__label" for="firstNameOther"> 
                        Please enter the otp
                    </label> 
                    <input type="text" name="otp"  class="c-input" placeholder="OTP">
                </div>
            </div>
        </div>
        <div class="row">
             <div class="col-lg-5">
                <button type="submit" id="auth_otp_btn" class="c-btn c-btn--info">Submit OTP</button>
                <button type="button" id="resendOtp" class="c-btn c-btn--danger" style="display: none">Resend OTP</button>
            </div>
        </div>
    </div>
</form>