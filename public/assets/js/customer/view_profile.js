$('#countrylist option[value="United States of America"]').insertBefore('#countrylist option[value="Albania"]');
$('#countrylist option[value="United Kingdom"]').insertBefore('#countrylist option[value="United States of America"]');
function updatepolicy()
{
    var mainUrl = $("#weburl").val();

        var refundrequest_status = 0;
        if($('#refundrequest').is(':checked'))
        {
            refundrequest_status = 1;
        }
        var refundrequestdamage_status = 0;
        if($('#refundrequestdamage').is(':checked'))
        {
            refundrequestdamage_status =1;
        }
        var refundrequestvalue = $('#refundrequestvalue').val();
        var refundrequestdamagevalue = $('#refundrequestdamagevalue').val();
        if(refundrequestvalue=='' || refundrequestdamagevalue=='')
        {
            Swal.fire("Please Enter Amount");
            return false;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: mainUrl+"/update_return_option",
            data:{"refundrequest_status":refundrequest_status,"refundrequest_value":refundrequestvalue,"refundrequestdamage_status":refundrequestdamage_status,"refundrequestdamage_value":refundrequestdamagevalue},
            dataType : "json",
            success: function(data) {
                if(data.success == 1){
                    Swal.fire("Success", "Update Successfull", "success");
                }else{
                    Swal.fire(data.message);
                }
            },
            error: function(data) {
                alert("Some error occured"); //location.reload(); return false;
            }
        });
}

function returnstatus()
{
	var mainUrl = $("#weburl").val();
	var return_address = $('#return_address').val();
	var return_label = '0';
	if($('#checkbox1').is(':checked'))
	{
		return_label = '1';
	}
	$.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        type: 'POST',
        url: mainUrl+"/update_return_status",
        data:{"return_address":return_address,'return_label_status':return_label},
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
function updateinpostreturn()
{
	var mainUrl = $("#weburl").val();
	var inpost_return = $('#inpost_return').val();
	$.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        type: 'POST',
        url: mainUrl+"/update_inpost_return",
        data:{"inpost_return":inpost_return},
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
function updatedelivery(id)
{
	var delivery_provider = $('#delivery_provider'+id).val();
	var tracking_url = $('#tracking_url'+id).val();
	var price = parseFloat($('#price'+id).val());
	var description = $('#description'+id).val();
	var status = $('#status'+id).val();
	var mainUrl = $("#weburl").val();
	$.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        type: 'POST',
        url: mainUrl+"/update_delivery",
        data:{"id":id,"delivery_provider":delivery_provider,"tracking_url":tracking_url,"price":price,"description":description,"status":status},
        dataType : "json",
        success: function(data) {
            if(data.status == 1){
            	$('#tbl_delivery_provider'+id).text(data.delivery_provider);
            	$('#tbl_tracking_url'+id).text(data.tracking_url);
                if(price=='' || price=='0')
                {
                    $('#tbl_price'+id).text('Free');
                }
                else
                {
                    $('#tbl_price'+id).text(data.price);
                }
                if(data.deliverystatus==1)
                {
                    $('#tbl_status'+id).attr('style', 'color:#00c292 !important');
                    //$('#tbl_status'+id).css("color","#056838","!important");
                    $('#tbl_status'+id).text('Live');
                }
                if(data.deliverystatus==0)
                {
                    //$('#tbl_status'+id).css("color","#D3D3D3","!important");
                    $('#tbl_status'+id).attr('style', 'color:#D3D3D3 !important');
                    $('#tbl_status'+id).text('Archive');
                }
            	$('#tbl_description'+id).text(data.description);

            	$('#deliverymodal'+id).modal('toggle');
                Swal.fire(
            "Success", "Delivery Information Updated Successfully", "success");
            }else{
                Swal.fire(data.message);
            }
        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });

}
function updatedeliveryoption()
{

    var delivery_option = 1;
    var mainUrl = $("#weburl").val();
    if($('#delivery_option').is(':checked'))
    {
        delivery_option = 0;

    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: 'POST',
        url: mainUrl+"/updatedeliveryoption",
        data:{"delivery_option":delivery_option},
        dataType : "json",
        success: function(data) {
            if(data.success == 1){
                Swal.fire(
            "Success", "Delivery Option Updated", "success");
            }else{
                Swal.fire(data.message);
            }
        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });
}
function updateorder()
{
	var order_status = 0;
	var mainUrl = $("#weburl").val();
	if($('#checkbox1').is(':checked'))
	{
		order_status = 1;
	}
	else
    {
        order_status = 0;
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
    else
    {
        update_message = 0;
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
    else
    {
        collect_status = 0;
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
    else
    {
        collection_status = 0;
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
    else
    {
        friend_status = 0;
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
	        		$('#delivery'+id).remove();

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
    	var description = $('#description').val();
    	$.ajaxSetup({
		  headers: {
		    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		  }
		});
		$.ajax({
	        type: 'POST',
	        url: mainUrl+"/add_delivery",
	        data:{'delivery_provider':delivery_provider,'tracking_url':tracking_url,'price':price,'description':description},
	        success: function(data) {
	        	if(data.status == 1)
	        	{   //$('#deliveries').css('display','block');
	        		$('#delivery_provider').val('');
	        		$('#tracking_url').val('');
	        		$('#price').val('');
	        		$('#description').val('');
                    $("#deliveries").append('<tr id=delivery'+data.id+'><td id="tbl_delivery_provider'+data.id+'">'+data.delivery_provider+'</td><td id="tbl_tracking_url'+data.id+'">'+data.tracking_url+'</td><td> <p class="text-muted font-medium price" id="tbl_price'+data.id+'">'+data.price+'</p></td><td><p class="text-muted font-medium description" id="tbl_description'+data.id+'">'+data.description+'</p></td><td><p class="text-muted font-medium description" id="tbl_status'+data.id+'" style="color:#00c292!important;">Live</p></td><td><button type="button" class="btn btn-light remove_courier" data-toggle="modal" data-target="#deliverymodal'+data.id+'"><i class="ti-pencil" aria-hidden="true"></i></button></td></tr>');
                    /*$('#popupdetails').append('<div class="modal fade" id=deliverymodal'+data.id+' tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="exampleModalLabel">Edit</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times</span></button></div><div class="modal-body"><div class="form-group"><form name="editdelivery"><label class="col-md-12">Courier / Delivery Provider</label><div class="col-md-12"><input type="text" id=delivery_provider'+data.id+' name="delivery_provider" placeholder="DHL / TNT / Yodel / Royal Mail" class="form-control form-control-line" value='+data.delivery_provider+'></div></div><div class="form-group"><label for="example-email" class="col-md-12">Tracking URL</label><div class="col-md-12"><input type="text" id=tracking_url'+data.id+' name="tracking_url" placeholder="https://courier-tracking-location" class="form-control form-control-line" name="example-email" value='+data.tracking_url+'></div></div><div class="form-group"><label for="example-email" class="col-md-12">Price</label><div class="col-md-12"><input type="text" id=price'+data.id+' name="price" placeholder="price" class="form-control form-control-line" value='+data.price+'></div></div><div class="form-group"><label for="example-email" class="col-md-12">Delivery Description</label><div class="col-md-12"><input type="text" id=description'+data.id+' name="description" placeholder="Delivery Description" class="form-control form-control-line" value='+data.description+'></div></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><button type="button" class="btn btn-primary" onclick=updatedelivery('+data.id')>Save changes</button></div></form></div></div></div>');*/
                $('#popupdetails').append('<div class="modal fade" id="deliverymodal'+data.id+'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"> <div class="modal-dialog" role="document"> <div class="modal-content"> <div class="modal-header"> <h5 class="modal-title" id="exampleModalLabel">Edit</h5> <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> </div> <div class="modal-body"> <div class="form-group"> <form name="editdelivery"><label class="col-md-12">Courier / Delivery Provider</label> <div class="col-md-12"> <input type="text" id="delivery_provider'+data.id+'" name="delivery_provider" placeholder="DHL / TNT / Yodel / Royal Mail" class="form-control form-control-line" value="'+data.delivery_provider+'"> </div> </div> <div class="form-group"> <label for="example-email" class="col-md-12">Tracking URL</label> <div class="col-md-12"> <input type="text" id="tracking_url'+data.id+'" name="tracking_url" placeholder="https://courier-tracking-location" class="form-control form-control-line" name="example-email" value="'+data.tracking_url+'"> </div> </div> <div class="form-group"> <label for="example-email" class="col-md-12">Price</label> <div class="col-md-12"> <input type="text" id="price'+data.id+'" name="price" placeholder="price" class="form-control form-control-line" value="'+data.price2+'"> </div> </div> <div class="form-group"> <label for="example-email" class="col-md-12">Delivery Description</label><p> <div class="col-md-12"> <textarea rows="10" cols="200" id="description'+data.id+'" name="description" placeholder="Delivery Description" class="form-control form-control-line deliverydescription" value="'+data.description+'">'+data.description+'</textarea> </div> </div><div class="form-group"> <label for="example-email" class="col-md-12">Status:</label><p> <div class="col-md-12"> <select class="form-control" name="status" id="status'+data.id+'"> <option value="0" selected>Archive</option> <option value="1" selected>Live</option> </select> </div> </div> </div> <div class="modal-footer"> <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> <button type="button" class="btn btn-primary" onclick="updatedelivery('+data.id+');">Save changes</button> </div> </form> </div> </div></div>');

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
                    Swal.fire('Updated','Profile Updated Successfully','success');
	        	setTimeout( 
                    function(){
                        $(".profile_danger").hide();
                        $(".profile_danger").hide();
                    } , 10000
                );
	        	}
                else
                {
                    Swal.fire('Error',data.message,'warning');
                }
	        	//hide messages
	        	
	        },
	    });
    });
});