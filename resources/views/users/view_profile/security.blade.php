<form id="update_auth">
    @csrf
    <input type="hidden" name="otp_val" value="">
    <input type="hidden" name="contact_no_hidden" value="">
    <div class="alert alert-danger auth_danger" style="display: none"></div>
    <div class="alert alert-success auth_success" style="display: none"></div>
    <div class="form-body">
        <!-- <h4>Security Info</h4> -->
        <label class="control-label text-success" style="font-size:24px">Security Info</label>
        <hr>
        <!-- name -->
        <div class="before_otp">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">
                            
                            2 step authentication
                            
                            <span id="2auth_label"> <b>{{$twoauth_val}}</b> </span>
                        
                        </label><Br />
                        <input type="checkbox" id="2_auth" {{$check_val}} class="form-control newjsswicth" name="auth_val"  />
                    </div>
                </div>
                <div class="col-md-6 2auth_form" style="{{$style_val}}">
                    <div class="form-group">
                        <label class="control-label">
                            Contact Number
                            @if($uDetails[0]['contact_verify_status'] == 0)
                            <i class="fa fa-exclamation-triangle no_verified" style="color:red" title="Verification Pending"></i>
                            @else
                                <i class="fa fa-check verified" style="color:green" title="Contact Verified"></i>
                            @endif
                        </label>
                        <input class="form-control" name="contact_mobile" type="number" id="phone_2auth"  placeholder="telephone number" value="{{$uDetails[0]['contact_no']}}">
                        
                    </div>
                </div>
            </div>

            
           

            <div class="form-actions">
                <button type="submit" class="btn btn-info" id="update_auth_button">Update</button>
            </div>
        </div>
        <div class="after_otp" style="display: none">
            <div class="row p-t-20">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Please enter the verification code</label>
                        <input type="text" name="otp"  class="form-control" placeholder="Verification Code">
                    </div>
                </div>
            </div>
            <div class="row p-t-20">
                <div class="col-md-6">
                    <button type="submit" id="auth_otp_btn" class="btn btn-success">Submit Code</button>
                    <button type="button" id="resendOtp" class="btn btn-danger">Resend Code</button>
                </div>
            </div>
        </div>
    </div>
</form>