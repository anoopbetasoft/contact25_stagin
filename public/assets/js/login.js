

window.ga = function () {
    ga.q.push(arguments)
};
ga.q = [];
ga.l = +new Date;
ga('create', 'UA-88739867-2', 'auto');
ga('send', 'pageview')


$( document ).ready(function() {

    $(".preloader").fadeOut();
    $('[data-toggle="tooltip"]').tooltip();

    /*** REGISTER **/
    /*
    register and generate otp
    */
    $("#signUp").on('click', function (e) {
                
        e.preventDefault();
        var ajax_url = $("#web_url").val()+'/register_otp';

        var data = new FormData($('#registerForm')[0]);
        var dial_code = $(".selected-dial-code").text();
        var contact_country = $("#registerForm").find(".country-list").find('li.country.highlight.active').attr("data-country-code");
        if(contact_country == null){
            contact_country = $("#registerForm").find(".country-list").find('li.country.preferred.active').attr("data-country-code");
        }
        data.append('dial_code', dial_code);
        data.append('contact_country', contact_country);

        $.ajax({
            url: ajax_url,
            data: data,
            type: "POST",
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.success == 2) { //submit otp //success
                    
                    $(".otp_register").css("display","block");
                    $(".first_register").css("display","none");
                    $('.signUpError').css('display','none').html('');
                    $('.signUpSuccess').css('display','block').html(data.message);
                    $('input[name="otpval"]').val(data.otpval);
                    $('#show_login_form').css('display','none');

                    setTimeout( 
                        function(){
                            $(".signUpError").hide();
                            $(".signUpSuccess").hide();
                        } , 10000
                    );
                    //show resend otp button after 1 minute
                    setTimeout( 
                        function(){
                            $("#resendOtp").show();
                        } , 60000
                    );
                }else if(data.success == 0){ //error
                    $(".otp_register").css("display","block");
                    $(".first_register").css("display","none");
                    $('.signUpError').css('display','block').html(data.message);
                    $('.signUpSuccess').css('display','none').html('');
                    $('input[name="otpval"]').val('');
                    setTimeout( 
                        function(){
                            $(".signUpError").hide();
                            $(".signUpSuccess").hide();
                        } , 4000
                    );
                }
                /*if (data.success == "200") {
                    show("login_form");
                }*/
                $.each(data.error, function (index, value) {
                    $("#" + index).html('<span class="error" style="color:red">' + value + '</span>');

                });
            }
        });
    });
    /*
    submit the otp 
    */
    $("#sendOtp").on('click', function (e) {
                
        e.preventDefault();
        $("#resendOtp").hide();
        var ajax_url = $("#web_url").val()+'/register';

        var data = new FormData($('#registerForm')[0]);
        var dial_code = $(".selected-dial-code").text();
        
        var contact_country = $("#registerForm").find(".country-list").find('li.country.highlight.active').attr("data-country-code");
        if(contact_country == null){
            contact_country = $("#registerForm").find(".country-list").find('li.country.preferred.active').attr("data-country-code");
        }
        data.append('dial_code', dial_code);
        data.append('contact_country', contact_country);

        $.ajax({
            url: ajax_url,
            data: data,
            type: "POST",
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.success == "0") { //submit otp 
                    $('.signUpError').css('display','block').html(data.message);
                    $('.signUpSuccess').css('display','none').html('');
                    setTimeout( 
                        function(){
                            $(".signUpError").hide();
                            $(".signUpSuccess").hide();
                        } , 4000
                    );
                }
                if(data.success == "3"){ //otp expired
                    $('.signUpError').css('display','block').html(data.message);
                    $('.signUpSuccess').css('display','none').html('');
                    $("#resendOtp").css('display','block');
                    //show resend otp button after 1 minute
                }
                if (data.success == "200") { //user created

                    show("login_form");
                    $(".otp_register").css("display","none");
                    $(".first_register").css("display","block");
                    $("#registerForm").trigger("reset");
                    $('.signUpError').css('display','none').html('');
                    $('.signUpSuccess').css('display','none').html('');

                }
                $.each(data.error, function (index, value) {
                    $("#" + index).html('<span class="error" style="color:red">' + value + '</span>');

                });
            }
        });
    });
    /*
    resend otp
    */
    $("#resendOtp").on('click', function (e) {
        $("#signUp").click();
        $(this).hide();
    });

    /*** LOGIN ***/
    $("#login-button").on('click', function (e) {
        
        e.preventDefault();

        var data = new FormData($('#loginForm')[0]);
        var ajax_url = $("#web_url").val()+'/login';
        $.ajax({
            url: ajax_url,
            data: data,
            type: "POST",
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.success == "200") {
                    window.location.href = ajax_url;
                }
                if(data.success == "2") { //send otp
                    $('.login_form_div').css('display','none');
                    $('.login_otp_div').css('display','block');
                    $('.LoginError').css('display','none').html('');
                    $('.LoginSuccess').css('display','block').html(data.message);
                    $('input[name="login_otp_val"]').val(data.otpval);
                    setTimeout( 
                        function(){
                            $(".LoginError").hide();
                            $(".LoginSuccess").hide();
                        } , 10000
                    );
                    //show resend otp button after 1 minute
                    setTimeout( 
                        function(){
                            $("#login-otp-resend").show();
                        } , 60000
                    );
                    $('#registerdiv_link').css('display','none');
                }
                if(data.success == "0"){ //some error
                    $(".login_form_div").css("display","none");
                    $(".login_otp_div").css("display","block");
                    $('.LoginError').css('display','block').html(data.message);
                    $('.LoginSuccess').css('display','none').html('');
                    $('input[name="login_otp_val"]').val('');
                    setTimeout( 
                        function(){
                            $(".LoginError").hide();
                            $(".LoginSuccess").hide();
                        } , 4000
                    );
                }

                $.each(data.error, function (index, value) {
                    $("#login_" + index).html();
                    $("#login_" + index).html('<span class="error" style="color:red">' + value + '</span>');

                });
            }

        });
    });

    /*
    Login user with otp
    */
    $("#login-otp").on('click', function (e) {
        
        e.preventDefault();

        var data = new FormData($('#loginForm')[0]);
        var ajax_url = $("#web_url").val()+'/login_otp';
        var login_url = $("#web_url").val()+'/login';
        $.ajax({
            url: ajax_url,
            data: data,
            type: "POST",
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.success == "200") { //login 
                    window.location.href = login_url;
                }
                if(data.success == "0"){ //error

                    $(".login_form_div").css("display","none");
                    $(".login_otp_div").css("display","block");
                    $('.LoginError').css('display','block').html(data.message);
                    $('.LoginSuccess').css('display','none').html('');
                    $('input[name="login_otp_val"]').val('');
                    setTimeout( 
                        function(){
                            $(".LoginError").hide();
                            $(".LoginSuccess").hide();
                        } , 4000
                    );
                }
                if(data.success == "3"){ //otp expired
                    $('.signUpError').css('display','block').html(data.message);
                    $('.signUpSuccess').css('display','none').html('');
                    $("#login-otp-resend").css('display','block');
                }
                if(data.success == '4'){ //invalid or wrong otp

                    $(".login_form_div").css("display","none");
                    $(".login_otp_div").css("display","block");
                    $('.LoginError').css('display','block').html(data.message);
                    $('.LoginSuccess').css('display','none').html('');
                    //$('input[name="login_otp_val"]').val('');
                    setTimeout( 
                        function(){
                            $(".LoginError").hide();
                            $(".LoginSuccess").hide();
                        } , 4000
                    );
                }

                $.each(data.error, function (index, value) {
                    $("#login_" + index).html();
                    $("#login_" + index).html('<span class="error" style="color:red">' + value + '</span>');

                });
            }

        });
    });
    /*
    resend otp
    */
    $("#login-otp-resend").on('click', function (e) {
        $("#login-button").click();
        $(this).hide();
    });

    /*** FORGOT PASSWORD***/
    $("#forgot_button").on('click', function (e) {
        
        e.preventDefault();
        var data = new FormData($('#forgotEmailForm')[0]);
        var ajax_url = $("#web_url").val()+'/forgot';
        $.ajax({
            url: ajax_url,
            data: data,
            type: "POST",
            processData: false,
            contentType: false,
            success: function (data) {
                $("#forgot_form").find('.forgot_mail_status').removeClass('alert alert-success alert alert-danger').html('');
                $("#input8").val('');
                setTimeout( function(){$("#forgot_form").find('.forgot_mail_status').hide();} , 4000);  
                if (data.status == 1) {
                    $("#forgot_form").find('.forgot_mail_status').addClass('alert alert-success').css('display','block').html(data.message);
                }else{
                    $("#forgot_form").find('.forgot_mail_status').addClass('alert alert-danger').css('display','block').html(data.message);
                }
            }
        });
    });

    //for country language in dropdown
    var countryData = $.fn.intlTelInput.getCountryData();
    $.each(countryData, function(i, country) {
        country.name = allCountries[i].name;
    });

    // initialise plugin
    $("#phone").intlTelInput({
        initialCountry: "gb",
        localizedCountries: null,
        separateDialCode: true,
        //utilsScript:"https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js",
    });

    $("#phone").keyup(function (event) {

        if ((event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0 || event.charCode == 109 || event.charCode == 189) {
            return true;
        }
    });

    $(document).on('keyup',"input", function () {
        if ($(this).val().length) {
            $(this).next('p').find('span').remove();
        }
    });
});
/*
show hide forms
*/
function show(elementId) {
    document.getElementById("login_form").style.display = "none";
    document.getElementById("register_form").style.display = "none";
    document.getElementById("forgot_form").style.display = "none";
    document.getElementById(elementId).style.display = "block";
}