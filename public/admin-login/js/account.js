$( document ).ready(function() {


	/***
	SECURITY TAB
	***/

	/* toggle */
	var elem = document.querySelector('.newjsswicth');
	var switchery = new Switchery(elem, 
	 { 
 		color: '#09b214', 
 		secondaryColor: '#d02828', 
 		jackColor: '#fff', 
 		jackSecondaryColor: '#fff',
 		size: 'small' 
 	});

	/* country code */
	var countryData = $.fn.intlTelInput.getCountryData();
	    $.each(countryData, function(i, country) {
	    country.name = allCountries[i].name;
	});

	 var contact_country_code =  $("#contact_country").val();
	if(contact_country_code == "" || contact_country_code == null){
		contact_country_code = 'gb';
	}

	$("#phone").intlTelInput({
	    initialCountry: contact_country_code,
	    localizedCountries: null,
	    separateDialCode: true,
	});

	$("#phone").keyup(function (event) {

	    if ((event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0 || event.charCode == 109 || event.charCode == 189) {
	        return true;
	    }
	});

	$("#phone_2auth").intlTelInput({
	    initialCountry: contact_country_code,
	    localizedCountries: null,
	    separateDialCode: true,
	});

	$("#phone_2auth").keyup(function (event) {

	    if ((event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0 || event.charCode == 109 || event.charCode == 189) {
	        return true;
	    }
	});


	$("#2_auth").on("change" , function() {
	 	if($(this).prop("checked") == true){
	 		$("#2auth_label").html('<b>Enabled</b>');
	       	$(".2auth_form").css("display","block");
	    }else{
	    	$("#2auth_label").html('<b>Disabled</b>');
	      	$(".2auth_form").css("display","none");
	    	
	    }
	 });

	$("#resendOtp").on('click', function (e) {
        $("#update_auth_button").click();
        $(this).hide();
    });


    $("#update_auth_button").on('click', function (e) {
	
		e.preventDefault();
		
		var mainUrl = $("#admin-url").val();
		//form
		//country name
		var contact_country = $("#update_auth").find(".country-list").find('li.country.highlight.active').attr("data-country-code");
		if(contact_country == "" || typeof contact_country === "undefined"){
			contact_country = $("#update_auth").find(".country-list").find('li.country.preferred.active').attr("data-country-code");
		}
		if (contact_country == "" || typeof contact_country === "undefined") {
			contact_country = $("#update_auth").find(".country-list").find('li.country.active').attr("data-country-code");
			
		}
		//country code
		var dial_code =  $("#update_auth").find(".selected-dial-code").text();
		if ($('#2_auth').is(":checked")){
			var auth_val = 1; //enable
		}else{
			var auth_val = 0; //disable
		}
		var formulario = $('#update_auth');

		var formD = new FormData($('#update_auth')[0]);
		
		formD.append("dial_code", dial_code); 
		formD.append("auth_val", auth_val); 
		formD.append("contact_country", contact_country); 
		
		//ajax
		$.ajaxSetup({
		  headers: {
		    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		  }
		});
		$.ajax({
	        type: 'POST',
	        url: mainUrl+"/admin_two_auth",
	        data:formD,
	        cache:false,
	        contentType: false,
	        processData: false,
	        success: function(data) {
	        	if(data.success == 0){ //error
	        		$("#update_auth").find('.auth_danger').css('display','block').html(data.message);
	        		$("#update_auth").find('.auth_success').css('display','none').html('');
	        		$("#update_auth").trigger('reset');
	        		$("#update_auth").trigger('reset');
	        		$("#update_auth").find("input[name='otp_val']").val('');
	        		$("#update_auth").find("input[name='contact_no_hidden']").val('');
	        	}  
	        	if(data.success == 1){ //settings updated
	        		$("#update_auth").find('.auth_danger').css('display','none').html('');
	        		$("#update_auth").find('.auth_success').css('display','block').html(data.message);
	        		setTimeout(function() {
					    location.reload();
					}, 1000);
	        	}
	        	if(data.success == 2){ //otp sent
	        		$("#update_auth").find('.auth_danger').css('display','none').html('');
	        		$("#update_auth").find('.auth_success').css('display','block').html(data.message);
	        		$("#update_auth").find(".before_otp").css('display','none');
	        		$("#update_auth").find(".after_otp").css('display','block');
	        		$("#update_auth").trigger('reset');
					$("#update_auth").find("input[name='otp_val']").val(data.otpval);
					$("#update_auth").find("input[name='contact_no_hidden']").val(data.contact_no_hidden);
	        	}
	        	//hide messages
	        	setTimeout( 
                    function(){
                        $(".auth_danger").hide();
                        $(".auth_success").hide();
                    } , 10000
                );
                //show resend otp button after 1 minute
                setTimeout( 
                    function(){
                        $("#resendOtp").show();
                    } , 60000
                );
	        	
	        },
	        error: function(data) {
	        	alert("Some error occured"); 
	        }
	    });
		//console.log(formData); return false;
	});

	$("#auth_otp_btn").on('click', function (e) {
	
		e.preventDefault();
		
		var mainUrl = $("#admin-url").val();
		//form
		var dial_code =  $("#update_auth").find(".selected-dial-code").text();
		if ($('#2_auth').is(":checked")){
			var auth_val = 1; //enable
		}else{
			var auth_val = 0; //disable
		}

		var formD = new FormData($('#update_auth')[0]);
		var contact_number = $("#update_auth").find("#phone_2auth").val();
		var dial_code =  $("#update_auth").find(".selected-dial-code").text();
		//country name
		var contact_country = $("#update_auth").find(".country-list").find('li.country.highlight.active').attr("data-country-code");
		if(contact_country == "" || typeof contact_country === "undefined"){
			contact_country = $("#update_auth").find(".country-list").find('li.country.preferred.active').attr("data-country-code");
		}
		if (contact_country == "" || typeof contact_country === "undefined") {
			contact_country = $("#update_auth").find(".country-list").find('li.country.active').attr("data-country-code");
			
		}
		formD.append("dial_code", dial_code); 
		formD.append("auth_val", auth_val);
		formD.append("contact_country", contact_country);  
		//ajax
		$.ajaxSetup({
		  headers: {
		    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		  }
		});
		$.ajax({
	        type: 'POST',
	        url: mainUrl+"/admin_two_auth_otp",
	        data:formD,
	        cache:false,
	        contentType: false,
	        processData: false,
	        success: function(data) {
	        	if(data.success == 0){ //error
	        		$("#update_auth").find('.auth_danger').css('display','block').html(data.message);
	        		$("#update_auth").find('.auth_success').css('display','none').html('');

	        	}  
	        	if(data.success == 1){ //settings updated
	        		$("#update_auth").find('.auth_danger').css('display','none').html('');
	        		$("#update_auth").find('.auth_success').css('display','block').html(data.message);
	        		setTimeout(function() {
					    location.reload();
					}, 1000);
	        		//$("#update_auth").trigger('reset');
	        	}
	        	if(data.success == 3){ //otp expired
	        		$("#update_auth").find('.auth_danger').css('display','block').html(data.message);
	        		$("#update_auth").find('.auth_success').css('display','none').html('');
	        		$("#update_auth").find("#resendOtp").css('display','block');
	        	}
	        	setTimeout( 
                    function(){
                        $(".auth_danger").hide();
                        $(".auth_success").hide();
                    } , 10000
                );
                //show resend otp button after 1 minute
                setTimeout( 
                    function(){
                        $("#resendOtp").show();
                    } , 60000
                );
	        	
	        },
	        error: function(data) {
	        	alert("Some error occured"); 
	        }
	    });
		//console.log(formData); return false;
	});

	/***
	PROFILE TAB
	***/

	/* avatar preview */
	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#preview_avatar').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#upload_avatar").change(function(){
        readURL(this);
    });

    /* edit avatr button */
    /*$("#editAvatar").click(function (){
    	$(this).text("Cancel");
    	$("#edit_avatar_input").css("display","block");
    });*/


});

function editAvatar(){
	var x = document.getElementById("edit_avatar_input");
	if (x.style.display === "none") {
		x.style.display = "block";
	} else {
		x.style.display = "none";
	}
}