

// product type 
function p_type_selected(data){
	var type = $(data).val();
	var type_div = $(data).find(':selected').attr('data-product_type');
	var type_row_div =  $(data).find(':selected').attr('data-product_type_row');
	var type_val = $(data).find(':selected').val();
	$("."+type_div).css('display','block');
	$("."+type_row_div).css('display','block');

	if(type_val == 1){
		$('.product_quality').prop('disabled', false);
		$('.product_quality').selectpicker('refresh');
		$('label.control-label.selling_product_price').html('Selling Price');
		$('.input-group-prepend.custom_dropdown_width.subsc_price_optn').css('display','none');
	}else if(type_val == 3){
		$('.product_quality').prop('disabled', true);
		$('.product_quality').selectpicker('refresh');
		$('label.control-label.selling_product_price').html('Subscription/Membership Price');
		$('.input-group-prepend.custom_dropdown_width.subsc_price_optn').css('display','block');
	}else{
		$('.product_quality').prop('disabled', true);
		$('.product_quality').selectpicker('refresh');
		$('label.control-label.selling_product_price').html('Selling Price');
		$('.input-group-prepend.custom_dropdown_width.subsc_price_optn').css('display','none');
	}
	//hiding other options
	$('#p_type_options > option').each(function(n) {

		var all_attributes = $(this).attr('data-product_type');
		var all_row_attribute = $(this).attr('data-product_type_row');
		if(all_attributes != type_div){
			$("."+all_attributes).css('display','none');
		}
		if(all_row_attribute != type_row_div){
			$("."+all_row_attribute).css('display','none');
		}
	});
}

//product sell to
function product_sell_to(data){
	var sell_to =  $(data).val();
	if(sell_to == "" || typeof sell_to === "undefined"){
		$(".item_sell_price").css('display','none');
		//$(".item_sell_price").find(".selling_price_val").attr("required", false);

	}else{
		$(".item_sell_price").css('display','block');
		//$(".item_sell_price").find(".selling_price_val").attr('required',true);
	}
}

//product lend to : item type
function product_lend_to(data){
	var lend_to = $(data).val();
	if(lend_to == "" || typeof lend_to === "undefined"){
		$(".item_lend_price").css('display','none');
		//$(".item_lend_price").find(".lending_price_val").attr("required", false);

	}else{
		$(".item_lend_price").css('display','block');
		//$(".item_lend_price").find(".lending_price_val").attr('required',true);
	}
}


//product service type : serive type
/*function service_type_selected(data){
	var radioDiv = $(data).attr('data-service_type');
	var selected_type_val =  $(data).val();
	$("."+radioDiv).css('display','block');
	$("input[name='p_service_option']").val(selected_type_val);

	var all_serivce_types = [];
	$('.service_div').find('.custom-radio').find('input[type="radio"]').each(function(n) {
		all_serivce_types[n] = $(this).attr('data-service_type');
		if($(this).attr('data-service_type') != radioDiv){
			$("."+$(this).attr('data-service_type')).css('display','none');
		}
	});
}
*/
function service_type_selected(data){
	var radioDiv = $(data).find(':selected').attr('data-service_type');
	var selected_type_val =  $(data).find(':selected').val();
	if((selected_type_val == '' || selected_type_val == null) && (radioDiv == '' || radioDiv == null)){
		alert('Please select a service type.'); return false;
	}
	$("."+radioDiv).css('display','block');
	$("input[name='p_service_option']").val(selected_type_val);	
	
	$('.service_div').find('.selectpicker option').each(function(n) {
		if($(this).attr('data-service_type') != null && $(this).attr('data-service_type') != radioDiv){
			$("."+$(this).attr('data-service_type')).css('display','none');
		}
	});
}

//product subscription type : subscription type
function subscription_type_selected(data){
	var radioDiv = $(data).find(':selected').attr('data-subs_type');
	var selected_type_val =  $(data).find(':selected').val();
	if((selected_type_val == '' || selected_type_val == null) && (radioDiv == '' || radioDiv == null)){
		alert('Please select a service type.'); return false;
	}
	$("."+radioDiv).css('display','block');
	$("input[name='p_subscription_option']").val(selected_type_val);
	
	
	$('.subs_member_div').find('.selectpicker option').each(function(n) {
		if($(this).attr('data-subs_type') != null && $(this).attr('data-subs_type') != radioDiv){
		//if($(this).attr('data-subs_type') != radioDiv){
			$("."+$(this).attr('data-subs_type')).css('display','none');
		}
	});
	
	//for delivered
	if(selected_type_val == 2){
		Swal.fire(
        	"What does 'Delivered' mean?", "Delivered means you will delivery a product to your customers' physical address.", "success")
	}
}
/*function subscription_type_selected(data){
	var radioDiv = $(data).attr('data-subs_type');
	var selected_type_val =  $(data).val();
	$("input[name='p_subscription_option']").val(selected_type_val);
	$("."+radioDiv).css('display','block');
	var all_subs_types = [];
	$('.subs_member_div').find('.custom-radio').find('input[type="radio"]').each(function(n) {
		all_subs_types[n] = $(this).attr('data-subs_type');
		if($(this).attr('data-subs_type') != radioDiv){
			$("."+$(this).attr('data-subs_type')).css('display','none');
		}
	});

	//for delivered
	if(selected_type_val == 2){
		alert("What does 'Delivered' mean?Delivered means you will delivery a product to your customers' physical address.");
	}
}*/


$(function() {
    
    $('#p_service_time').bootstrapMaterialDatePicker({ format: 'dddd DD MMMM YYYY - HH:mm' });
	$('#p_subs_time').bootstrapMaterialDatePicker({ format: 'dddd DD MMMM YYYY - HH:mm' });



});



/* file preview*/

function readURL(input) {

	if(input.files.length == 1){
		console.log(input.files[0]);
		if (input.files && input.files[0]) {
		    var reader = new FileReader();
		    reader.onload = function(e) {
		    	$("img#displaySingle").attr('src', e.target.result).css('display','block');
		    	$("#carouselProControls").css("display","none");
		    }
		    
		    reader.readAsDataURL(input.files[0]);
		}
	}else{
		$("img#displaySingle").attr('src', "#").css('display','none');
		$("#carouselProControls").css("display", "block");
		$("#carouselProControls").find(".carousel-inner").html('');
		if (input.files) {
			
			$(input.files).each(function(n,data) {
				
				var reader = new FileReader();
			    	reader.onload = function(e) {
			    	if(n == 0 ){
			    		var activeSlider = 'active';
			    	}else{
			    		var activeSlider = '';
			    	}
			    	$("#carouselProControls").find(".carousel-inner").append('<div class="carousel-item  '+activeSlider+' "><img class="d-block w-100" src="'+e.target.result+'" alt="Second slide"> </div>');
			    	
			    }
			    reader.readAsDataURL(data);
			});
		}
	}
}

$("#imgInp").change(function() {
  readURL(this);
  	$('#carouselProControls').carousel({
	  interval: 2000
	})
});