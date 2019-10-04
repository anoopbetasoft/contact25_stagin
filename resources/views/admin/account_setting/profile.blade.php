<form id="update_setting" method="post">
    <div class="row">
        
        <div class="col-lg-2 u-text-center">
            <div class="c-avatar c-avatar--xlarge u-inline-block">
                @if(empty($uDetails[0]['avatar']))
                <img class="c-avatar__img" id="preview_avatar" src="{{asset('assets/images/icon/staff.png')}}" alt="Avatar">
                @else
                <img class="c-avatar__img" id="preview_avatar" src="img/avatar-200.jpg" alt="Avatar">
                @endif
            </div>

            <a class="u-block u-color-primary" id="#editAvatar" onclick="editAvatar()">Edit Avatar</a>
            

            <div class="c-avatar c-avatar--xlarge u-inline-block" id="edit_avatar_input" style="display:none !important">
                <input type="file" id="upload_avatar" name="pic" accept="image/*">
            </div>
        </div>
        <div class="col-lg-6">
            
                <div class="c-field u-mb-small">
                    <label class="c-field__label" for="name">Name</label> 
                    <input class="c-input" type="text" id="name" placeholder="Jason" value="{{$uDetails[0]['name']}}">
                </div>
                <div class="c-field u-mb-small">
                    <label class="c-field__label" for="email">Email</label>
                    <input class="c-input" id="email" type="email" placeholder="jason@clark.com" value="{{$uDetails[0]['email']}}">
                </div>
                <div class="c-field u-mb-small">

                    <label class="c-field__label" for="bio">Contact No</label>

                    <input class="c-input" type="number" name="contact_no" id="phone"  placeholder="telephone number" value="{{$uDetails[0]['contact_no']}}">
                    @if(empty($uDetails[0]['contact_verified_at']))
                    <i class="fa fa-exclamation-triangle" style="color:red" title="Verification Pending"></i>
                    @else
                    <i class="fa fa-check" style="color:green" title="Contact Verified"></i>
                    @endif
                    
                    

                    <!-- otp -->
                    <input class="c-input" type="number" id="verfiy_otp"  name="verfiy_otp"  placeholder="Enter OTP" value="" style="display: none">


                </div>
                <div class="c-field u-mb-small">
                    <input type="submit" class="c-btn c-btn--info c-btn--fullwidth" value="update">
                </div>
        </div>
         

        <!-- <div class="col-lg-5">
            <div class="c-field u-mb-small">
                <label class="c-field__label" for="companyName">Company Name</label>
                <input class="c-input" id="companyName" type="text" placeholder="Dashboard Ltd.">
            </div>

            <div class="c-field u-mb-small">
                <label class="c-field__label" for="website">Website</label>
                <input class="c-input" id="website" type="text" placeholder="zawiastudio.com">
            </div>  

            <label class="c-field__label" for="socialProfile">Social Profiles</label>

            <div class="c-field has-addon-left u-mb-small">
                <span class="c-field__addon u-bg-twitter">
                    <i class="fa fa-twitter u-color-white"></i>
                </span>
                <input class="c-input" id="socialProfile" type="text" placeholder="Clark">
            </div>

            <div class="c-field has-addon-left">
                <span class="c-field__addon u-bg-facebook">
                    <i class="fa fa-facebook u-color-white"></i>
                </span>
                <input class="c-input" type="text" placeholder="Clark">
            </div>
        </div> -->
        
    </div>
</form>