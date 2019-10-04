$('#countrylist option[value="United States of America"]').insertBefore('#countrylist option[value="Albania"]');
$('#countrylist option[value="United Kingdom"]').insertBefore('#countrylist option[value="United States of America"]');

function updateorder()
{
	var order_status = 0;
	var mainUrl = $("#weburl").val();
	if($('#checkbox1').is(':checked'))
	{
		order_status = 1;
	}
	$.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        type: 'POST',
        url: mainUrl+"/update_communication",
        data:{"order_status":order_status},
        dataType : "json",
        success: function(data) {
            if(data.success == 1){
            
                Swal.fire(
            "Success", "Update Successfull", "success");
            }else{
            	
                Swal.fire(data.message);
            }
        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });

}
function updatemessage()
{

	var update_message = 0;
	var mainUrl = $("#weburl").val();
	if($('#checkbox2').is(':checked'))
	{
		update_message = 1;
	}
	$.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        type: 'POST',
        url: mainUrl+"/update_communication",
        data:{"update_message":update_message},
        dataType : "json",
        success: function(data) {
            if(data.success == 1){
                Swal.fire(
            "Success", "Update Successfull", "success");
            }else{
                Swal.fire(data.message);
            }
        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });
}
function updatecollectreminder()
{

	var collect_status = 0;
	var mainUrl = $("#weburl").val();
	if($('#checkbox3').is(':checked'))
	{
		collect_status = 1;
	}
	$.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        type: 'POST',
        url: mainUrl+"/update_communication",
        data:{"collect_status":collect_status},
        dataType : "json",
        success: function(data) {
            if(data.success == 1){
                Swal.fire(
            "Success", "Update Successfull", "success");
            }else{
                Swal.fire(data.message);
            }
        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });
}
function updatecollectionreminder()
{

	var collection_status = 0;
	var mainUrl = $("#weburl").val();
	if($('#checkbox4').is(':checked'))
	{
		collection_status = 1;
	}
	$.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        type: 'POST',
        url: mainUrl+"/update_communication",
        data:{"collection_status":collection_status},
        dataType : "json",
        success: function(data) {
            if(data.success == 1){
                Swal.fire(
            "Success", "Update Successfull", "success");
            }else{
                Swal.fire(data.message);
            }
        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });
}
function updatefriendstatus()
{
	var friend_status = 0;
	var mainUrl = $("#weburl").val();
	if($('#checkbox5').is(':checked'))
	{
		friend_status = 1;
	}
	$.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        type: 'POST',
        url: mainUrl+"/update_communication",
        data:{"friend_status":friend_status},
        dataType : "json",
        success: function(data) {
            if(data.success == 1){
                Swal.fire(
            "Success", "Update Successfull", "success");
            }else{
                Swal.fire(data.message);
            }
        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });
}
function updateinpost()
{
	var inpost_status = $('#inpost_status').val();
	var mainUrl = $("#weburl").val();
	$.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        type: 'POST',
        url: mainUrl+"/update_delivery",
        data:{"inpost_status":inpost_status},
        dataType : "json",
        success: function(data) {
            if(data.success == 1){
                Swal.fire(
            "Success", "Update Successfull", "success");
            }else{
                Swal.fire(data.message);
            }
        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });
}
function deletedelivery(data)
{
	var id = data;
	var mainUrl = $("#weburl").val();
	$.ajaxSetup({
		  headers: {
		    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		  }
		});
		$.ajax({
	        type: 'POST',
	        url: mainUrl+"/delete_delivery",
	        data:{'id':id},
	        success: function(data) {
	        	if(data.status == 1)
	        	{
	        		$('#delivery'+id).css('display','none');

	        	}

                
	        	if(data.status == 0){ //error
	        		/*$("#update_profile").find('.profile_danger').css('display','block').html(data.message);
	        		$("#update_profile").find('.profile_success').css('display','none').html('');
	        		$("#update_profile").trigger('reset');*/
	        		Swal.fire('Error',data.message,'warning');
	        	}  
	        	
	        	//hide messages
	        	
	        },
	    });

}
$("#deliveryclick").click(function (){
    	var mainUrl = $("#weburl").val();
    	//var formD = new FormData($('#delivery')[0]);
    	var formD = $('#delivery').serialize();
    	var delivery_provider = $('#delivery_provider').val();
    	var price = $('#price').val();
    	var tracking_url = $('#tracking_url').val();
    	$.ajaxSetup({
		  headers: {
		    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		  }
		});
		$.ajax({
	        type: 'POST',
	        url: mainUrl+"/add_delivery",
	        data:{'delivery_provider':delivery_provider,'tracking_url':tracking_url,'price':price},
	        success: function(data) {
	        	if(data.status == 1)
	        	{   $('#deliveries').css('display','block');
	        		$('#delivery_provider').val('');
	        		$('#tracking_url').val('');
                    $("#deliveries").append('<div id=delivery'+data.id+'><div class="row"><div class="col-md-2 col-lg-2 col-xlg-2 col-sm-3 col-xs-6" style="width: auto; min-width: 0;"><div class="card" style="margin-bottom: 2px;"></div><p class="text-muted font-medium">'+data.delivery_provider+'</p></div><div class="col-md-5 col-lg-5 col-xlg-5 col-sm-6 col-xs-12" style="width: auto; min-width: 0;"><div class="card" style="margin-bottom: 2px;"></div><p class="text-muted font-medium">'+data.tracking_url+'</p></div><div class="col-md-2 col-lg-2 col-xlg-2 col-sm-3 col-xs-6" style="width: auto; min-width: 0;"><div class="card" style="margin-bottom: 2px;"></div><p class="text-muted font-medium">'+data.price+'</p></div><div class="col-md-3 col-lg-3 col-xlg-3 col-sm-6 col-xs-3"><div class="card padding-top:0px; margin-bottom: 12px;"><button type="button" class="btn btn-light remove_courier" data-courier_id="11" onclick="deletedelivery('+data.id+');"><i class="ti-close" aria-hidden="true"></i></button></div></div></div></div>');

	        	}

                
	        	if(data.status == 0){ //error
	        		/*$("#update_profile").find('.profile_danger').css('display','block').html(data.message);
	        		$("#update_profile").find('.profile_success').css('display','none').html('');
	        		$("#update_profile").trigger('reset');*/
	        		$('#delivery_provider').val('');
	        		$('#tracking_url').val('');
	        		Swal.fire('Error',data.message,'warning');
	        	}  
	        	
	        	//hide messages
	        	setTimeout( 
                    function(){
                        $(".profile_danger").hide();
                        $(".profile_danger").hide();
                    } , 10000
                );
	        },
	    });
    });
$( document ).ready(function() {


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

	/**

	2 step Authication

	**/
	
	$("#update_auth_button").on('click', function (e) {
	
		e.preventDefault();
		
		var mainUrl = $("#weburl").val();
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
	        url: mainUrl+"/two_auth",
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
					}, 5000);
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
	        	alert("Some error occured"); //location.reload(); return false;
	        }
	    });
		//console.log(formData); return false;
	});

	$("#auth_otp_btn").on('click', function (e) {
	
		e.preventDefault();
		
		var mainUrl = $("#weburl").val();
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
	        url: mainUrl+"/two_auth_otp",
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
					}, 5000);
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
	        	alert("Some error occured"); //location.reload(); return false;
	        }
	    });
		//console.log(formData); return false;
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

    /***
    EDIT PROFILE
    ***/
    $("#update_profile").submit(function (e){
    	
    	e.preventDefault();
    	var mainUrl = $("#weburl").val();
    	var formD = new FormData($('#update_profile')[0]);	

    	$.ajaxSetup({
		  headers: {
		    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		  }
		});
		$.ajax({
	        type: 'POST',
	        url: mainUrl+"/edit_profile",
	        data:formD,
	        cache:false,
	        contentType: false,
	        processData: false,
	        success: function(data) {
	        	$.each(data.error, function (index, value) {
                    $("#" + index).html('<span class="error" style="color:red">' + value + '</span>');

                });
	        	if(data.success == 0){ //error
	        		$("#update_profile").find('.profile_danger').css('display','block').html(data.message);
	        		$("#update_profile").find('.profile_success').css('display','none').html('');
	        		//$("#update_profile").trigger('reset');
	        		Swal.fire('Oops','Please set up your location. Just start typing your address into the location box to help us pinpoint your exact location.','error');
	        	}  
	        	if(data.success == 1){ //settings updated
	        		$("#update_profile").find('.profile_danger').css('display','none').html('');
	        		$("#update_profile").find('.profile_success').css('display','block').html(data.message);
	        		$('#addproductbutton').removeAttr("onclick");
	        		$('#addproductbutton').attr("href","/add_product");
	        	setTimeout( 
                    function(){
                        $(".profile_danger").hide();
                        $(".profile_danger").hide();
                    } , 10000
                );
	        	}
	        	//hide messages
	        	
	        },
	    });
    });
});