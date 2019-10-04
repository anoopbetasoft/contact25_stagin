//Function to display Terms & Condition in SWAL
function terms()
{
  Swal.fire('Terms & Condition','content here','success');
}

//TO show check before completing order div 
function showcollectiondiv()
{
  $('.collection_control').css('display','block');
}
function checkboxvalidate(timecount,rangecount)
{
  
  var quantity = $('#p_quantity').val();
  var len =$('.checkboxselect:checked').length;
  var range = rangecount + 1;
  if(len>quantity)
  {
    //$('.checkbox_'+timecount+'_'+rangecount).prop('checked', false);
    $("#checkboxdiv_"+timecount+"_"+range).next('.icheckbox_minimal-red').removeClass('checked');
    $("#checkbox_"+timecount+"_"+range).prop('checked',false);
    Swal.fire('Sorry','You cannot book more than '+quantity+' slots','warning');
    
  }
  else
  {
    $('.collection_control').css('display','block');
  }

}
function checkboxvalidate2(data)
{
  var quantity = $('#p_quantity').val();
  var len =$('.checkboxselect:checked').length;
  if(len>quantity)
  {
    //$('.checkbox_'+timecount+'_'+rangecount).prop('checked', false);
    $(data).prop('checked', false);
    Swal.fire('Sorry','You cannot book more than '+quantity+' slots','warning');
    
  }
  else
  {
    $('.collection_control').css('display','block');
  }
}


//show hide lending options
function buyOption_change(data) {
	
	var type_div = $(data).find(':selected').attr('data-purchange_type');

	$("#p_quantity").selectpicker('setStyle', '');

	if(type_div == 'buy_options'){
		$("#p_quantity").selectpicker('setStyle', 'btn-warning btn-secondary', 'remove');
		$("#p_quantity").selectpicker('setStyle', 'btn-info btn-secondary');
		$(data).selectpicker('setStyle', 'btn-warning btn-secondary', 'remove');
		$(data).selectpicker('setStyle', 'btn-info btn-secondary');
		displayPay();
		$('.selectpicker').selectpicker('refresh');

	}else{

		$("#p_quantity").selectpicker('setStyle', 'btn-info btn-secondary', 'remove');
		$("#p_quantity").selectpicker('setStyle', 'btn-warning btn-secondary');
		$(data).selectpicker('setStyle', 'btn-info btn-secondary', 'remove');
		$(data).selectpicker('setStyle', 'btn-warning btn-secondary');

		$(".col-md-12.payment_div").css('display','none');
		$('.selectpicker').selectpicker('refresh');
	}

	$("."+type_div).css('display','block');
	$('#purchase_type > option').each(function(n) {

		var all_attributes = $(this).attr('data-purchange_type');
		
		if(all_attributes != type_div){
			$("."+all_attributes).css('display','none');
		}
	});

	
	$('.selectpicker').selectpicker('refresh');
}

//changing lending price
function lendOption(data){
	var optionVal = $(data).find(':selected').val();
	var lending_price = $("input[name='lending_price']").val();
	var new_price = optionVal * lending_price;
	var quantity = $("#p_quantity").val();
	var new_price_update = new_price*quantity;
	$("#purchase_type").find("option:selected").text("Lend for \u00A3"+new_price_update);
	$('.selectpicker').selectpicker('refresh');
	$('#purchase_val').val(new_price_update);
	displayPay();
	
	//$(".col-md-12.payment_div").css('display','block');
}


//changing subs price
function subscribeOption(data){

	var optionVal = $(data).find(':selected').val();
	var subs_price = $("input[name='subscribe_price']").val();
	var new_price = optionVal * subs_price;

	var quantity = $("#p_quantity").val();
	var new_price_update = new_price*quantity;

	$("#purchase_type").find("option:selected").text("Subscribe for \u00A3"+new_price_update);
	$('.selectpicker').selectpicker('refresh');
	$('#purchase_val').val(new_price_update);
	$(".col-md-12.payment_div").css('display','block');
	displayPay();
}

//changing quantity
function quantity_change(data){
	var quantity = $(data).val();
  var currency_symbol = $("input[name='currency_symbol']").val();

	var purchase_type = $("#purchase_type").find("option:selected").attr('data-purchange_type');
	

	if(purchase_type == 'buy_options'){

		var raw_price = $("input[name='selling_price']").val();
		var new_price_update = raw_price*quantity;
		$('#purchase_val').val(new_price_update);
		$("#purchase_type").find("option:selected").text("Buy for "+currency_symbol+new_price_update);

	}else if(purchase_type == 'lending_options'){  
		var optionVal = $("select[name='buy_product_option']").find(':selected').val();
		if(optionVal == 0){
			optionVal = 1;
		}
		var raw_price = $("input[name='lending_price']").val();
		var new_price_update = raw_price*quantity*optionVal;
		$('#purchase_val').val(new_price_update);
		$("#purchase_type").find("option:selected").text("Lend for "+currency_symbol+new_price_update);

	}else if(purchase_type == 'service_options'){
    if(quantity>'1')
    {
      $('.radiocheck').css('display','none');
      $('.checkboxcheck').css('display','block');
      $('.checkboxselect').prop('checked',false);
      $('.radioselect').prop('checked',false);
      $('.radioselect').parent().removeClass('checked');

    }
    else
    {
      $('.radiocheck').css('display','block');
      $('.checkboxcheck').css('display','none');
      $('.checkboxselect').prop('checked',false);
      $('.radioselect').prop('checked',false);
      $('.radioselect').parent().removeClass('checked');
    }

		var optionVal = $("select[name='buy_product_option']").find(':selected').val();
    var service_time = $("input[name='service_time']").val();
    var service_time_type = $("input[name='service_time_type']").val();
		//console.log(optionVal);
    if(optionVal == 0){
			optionVal = 1;
		}
		var raw_price = $("input[name='selling_price']").val();
		var new_price_update = quantity*optionVal;
    var new_service_time = quantity*service_time;
    $('#purchase_val').val(new_price_update);
		$("#purchase_type").find("option:selected").text("Buy for "+currency_symbol+new_price_update+'/'+new_service_time+''+service_time_type);

	}else{
		var optionVal = $("select[name='buy_product_option']").find(':selected').val();
		if(optionVal == 0){
			optionVal = 1;
		}
		var raw_price = $("input[name='subscribe_price']").val();
		var new_price_update = raw_price*quantity*optionVal;
		$('#purchase_val').val(new_price_update);
		$("#purchase_type").find("option:selected").text("Subscribe for "+currency_symbol+new_price_update);
	}

	$('.selectpicker').selectpicker('refresh');
}
function hidecollectitem()
{
    $('#buttons').css('display','flex');
    $('#collect_item').css('display','none');
    $('.payment_div').css('display','none');
}
function hidedeliveritem()

{
    $('#buttons').css('display','flex');
    $('#deliver_item').css('display','none');
    $('.payment_div').css('display','none');
    $('#customCheck5').prop('checked','false');
}

//choosing collection type
function collection_type(data){
	$('#buttons').css('display','none');
	var show_col_type = $(data).attr('data-show_coll_type');
	$("#"+show_col_type).css('display','block');
	var collection_type = $(data).attr('data-hide_coll_type');
	$("#"+collection_type).css('display','none');
  //alert(show_col_type);
  if(show_col_type=='deliver_item')
  {
    $('.col-md-12.payment_div').css('display','block');
    $('input[name="returncheck_deliver"]').prop('checked', true); 
    //$('input[name="slot_time"]').prop('checked', false);
    //$('input[name="timecheck_collect"]').prop('checked', false);
    //$('input[name="directioncheck_collect"]').prop('checked', false);
    //$('input[name="returncheck_collect"]').prop('checked', false); 
    braintreeUI();
  }
  else
  {
    $(".col-md-12.payment_div").css('display','none');
    $('input[name="returncheck_deliver"]').prop('checked', false); 
    //radio and checkboxes
    $('input[name="slot_time"]').prop('checked', false);
    /*$('input[name="timecheck_collect"]').prop('checked', false);
    */$('input[name="directioncheck_collect"]').prop('checked', false);
    $('input[name="returncheck_collect"]').prop('checked', false); 
  }

	
	
	
}
//display rest of the time slots
function showAll_slots(data,prev){
	//alert(data);
	$('#'+data).css('display','block');
	$('#'+prev).css('display','none');
	//$('#'+data-1).css('display','none');
	//$('#'+data).toggle('slow');
	//$(data).find('span').toggleClass('fa fa-angle-up fa fa-angle-down');
}
// display previous time slots
function showprevious_slots(data,next){
	//alert('called');
	$('#'+data).css('display','block');
	$('#'+next).css('display','none');
	//$('#'+data+1).css('display','none');
	//$('#'+data).toggle('slow');
}

//show time details
function timeslot_day(data){
	var id = $(data).attr('id');
	//alert(id);
	$("ul."+id+"").toggle('slow');
	$('.product_page_ul').not("ul."+id+"").hide('slow');
}	

function timeslot_selected(){
  
  /*var time_slot = $('label[for="' + $(data).attr('id') + '"]').html();
	var time_slot_date = $(data).attr('data-raw_date');
	var id = $(data).attr('id');
	var datetime = $(id).val();
	console.log(datetime);
	console.log(time_slot_date);
	$('.final_collect_time').html(time_slot_date+ " between "+time_slot);
	$('#collectiondate').html(data);*/
}

function braintreeUI(data)
{
$(".col-md-12.payment_div").css('display','block');
var form = document.querySelector('#my-sample-form');
var submit = document.querySelector('input[type="submit"]');
var mainUrl = $("#weburl").val();
var authorization_token = $("input[name='initial_token']").val();
var clientToken = document.querySelector("#clientToken").value;
var braintree_id = document.querySelector("#braintree_id").value;
var amount = document.querySelector('#purchase_val').value;
var product_id = $("input[name='product_id']").val();
var formData = $( "#add_to_cart" ).serialize();
//alert(amount);

if(clientToken == ""){   // IF CLIENT TOKEN IS BLANK

braintree.client.create({
  authorization: authorization_token,
  container: '#dropin-container'
}, function (err, clientInstance) {
  if (err) {
    console.error(err);
    return;
  }

  // Create input fields and add text styles  
  braintree.hostedFields.create({
    client: clientInstance,
    styles: {
      'input': {
        'color': '#282c37',
        'font-size': '16px',
        'transition': 'color 0.1s',
        'line-height': '3'
      },
      // Style the text of an invalid input
      'input.invalid': {
        'color': '#E53A40'
      },
      // placeholder styles need to be individually adjusted
      '::-webkit-input-placeholder': {
        'color': 'rgba(0,0,0,0.6)'
      },
      ':-moz-placeholder': {
        'color': 'rgba(0,0,0,0.6)'
      },
      '::-moz-placeholder': {
        'color': 'rgba(0,0,0,0.6)'
      },
      ':-ms-input-placeholder': {
        'color': 'rgba(0,0,0,0.6)'
      }

    },
    // Add information for individual fields
    fields: {
      number: {
        selector: '#card-number',
        placeholder: '1111 1111 1111 1111'
      },
      cvv: {
        selector: '#cvv',
        placeholder: '123'
      },
      expirationDate: {
        selector: '#expiration-date',
        placeholder: '10 / 2019'
      }
    }
  }, function (err, hostedFieldsInstance) {
    if (err) {
      console.error(err);
      return;
    }

    hostedFieldsInstance.on('validityChange', function (event) {
      // Check if all fields are valid, then show submit button
      var formValid = Object.keys(event.fields).every(function (key) {
        return event.fields[key].isValid;
      });

      if (formValid) {
        //$('#button-pay').addClass('show-button');
        //$('#submitbutton').css('display','block');
      	$('#button-pay').css('display','block');
      } else {
      	//$('#button-pay').removeClass('show-button');
        //$('#submitbutton').css('display','none');
      	$('#button-pay').css('display','none');
      }
    });

    hostedFieldsInstance.on('empty', function (event) {
      $('header').removeClass('header-slide');
      $('#card-image').removeClass();
      //$('#submitbutton').css('display','none');
      //$('#button-pay').removeClass('show-button');
      $('#button-pay').css('display','none');
      $(form).removeClass();
    });

    hostedFieldsInstance.on('cardTypeChange', function (event) {
      // Change card bg depending on card type
      if (event.cards.length === 1) {
        $(form).removeClass().addClass(event.cards[0].type);
        $('#card-image').removeClass().addClass(event.cards[0].type);
        $('header').addClass('header-slide');
        //$('#button-pay').css('display','block');
        
        // Change the CVV length for AmericanExpress cards
        if (event.cards[0].code.size === 4) {
          hostedFieldsInstance.setAttribute({
            field: 'cvv',
            attribute: 'placeholder',
            value: '1234'
          });
        } 
      } else {
        hostedFieldsInstance.setAttribute({
          field: 'cvv',
          attribute: 'placeholder',
          value: '123'
        });
      }
    });

    submit.addEventListener('click', function (event) {
      event.preventDefault();
      $('.preloader').css('display','block');

      hostedFieldsInstance.tokenize(function (err, payload) {
        if (err) {
          console.error(err);
          return;
        }

        // This is where you would submit payload.nonce to your server
        $.get(mainUrl+'/payment/process', {payload,amount,braintree_id,product_id,formData}, function (response) {
                       // console.log(response.success);
                        //console.log(formData);
                        if (response.success=='1') {
                          $('.preloader').css('display','block');
                           Swal.fire({
                                title: "Success!",
                                text:  "Your Payment Is Successfull",
                                type: "success",
                                timer: 3000,
                                showConfirmButton: false
                            });
                            //window.setTimeout(function(){ } ,5000);
                             window.location.replace(mainUrl+'/success/'+response.order_id);
                           
						     
						        
                        } else {
                            $('.preloader').css('display','none');
                         
                            Swal.fire({
                                title: "Oops!",
                                text:  "Your Payment Is Failed",
                                type: "error",
                                timer: 3000,
                                showConfirmButton: false
                            });
                        window.setTimeout(function(){ } ,5000);
                       
                        }
                    }, 'json');
        //alert('Submit your nonce to your server here!');
      });
    }, false);
  });
});

}  // IF CLIENT TOKEN IS BLANK

else  // IF CLIENT TOKEN IS NOT BLANK
{
	braintree.client.create({
  authorization: clientToken,
  container: '#dropin-container'
}, function (err, clientInstance) {
  if (err) {
    console.error(err);
    return;
  }

  // Create input fields and add text styles  
  braintree.hostedFields.create({
    client: clientInstance,
    styles: {
      'input': {
        'color': '#282c37',
        'font-size': '16px',
        'transition': 'color 0.1s',
        'line-height': '3'
      },
      // Style the text of an invalid input
      'input.invalid': {
        'color': '#E53A40'
      },
      // placeholder styles need to be individually adjusted
      '::-webkit-input-placeholder': {
        'color': 'rgba(0,0,0,0.6)'
      },
      ':-moz-placeholder': {
        'color': 'rgba(0,0,0,0.6)'
      },
      '::-moz-placeholder': {
        'color': 'rgba(0,0,0,0.6)'
      },
      ':-ms-input-placeholder': {
        'color': 'rgba(0,0,0,0.6)'
      }

    },
    // Add information for individual fields
    fields: {
      number: {
        selector: '#card-number',
        placeholder: '1111 1111 1111 1111'
      },
      cvv: {
        selector: '#cvv',
        placeholder: '123'
      },
      expirationDate: {
        selector: '#expiration-date',
        placeholder: '10 / 2019'
      }
    }
  }, function (err, hostedFieldsInstance) {
    if (err) {
      console.error(err);
      return;
    }

    hostedFieldsInstance.on('validityChange', function (event) {
      // Check if all fields are valid, then show submit button
      var formValid = Object.keys(event.fields).every(function (key) {
        return event.fields[key].isValid;
      });

      if (formValid) {
        //$('#button-pay').addClass('show-button');
        //$('#submitbutton').css('display','block');
      	$('#button-pay').css('display','block');
      } else {
        //$('#button-pay').removeClass('show-button');
      	$('#button-pay').css('display','none');
      }
    });

    hostedFieldsInstance.on('empty', function (event) {
      $('header').removeClass('header-slide');
      $('#card-image').removeClass();
      $('#button-pay').css('display','none');
      $(form).removeClass();
      //$('#button-pay').removeClass('show-button');
    	
    });

    hostedFieldsInstance.on('cardTypeChange', function (event) {
      // Change card bg depending on card type
      if (event.cards.length === 1) {
        $(form).removeClass().addClass(event.cards[0].type);
        $('#card-image').removeClass().addClass(event.cards[0].type);
        $('header').addClass('header-slide');
        //$('#button-pay').css('display','block');
        
        // Change the CVV length for AmericanExpress cards
        if (event.cards[0].code.size === 4) {
          hostedFieldsInstance.setAttribute({
            field: 'cvv',
            attribute: 'placeholder',
            value: '1234'
          });
        } 
      } else {
        hostedFieldsInstance.setAttribute({
          field: 'cvv',
          attribute: 'placeholder',
          value: '123'
        });
      }
    });

    submit.addEventListener('click', function (event) {
      event.preventDefault();
      $('.preloader').css('display','block');


      hostedFieldsInstance.tokenize(function (err, payload) {
        if (err) {
          console.error(err);
          return;
        }

        // This is where you would submit payload.nonce to your server
        $.get(mainUrl+'/payment/process', {payload,amount,braintree_id,product_id,formData}, function (response) {
                        //console.log(response.order_id);
                       // console.log(response);
                      //  console.log(formData);
                        if (response.success=='1') {
                           $('.preloader').css('display','block');
                           Swal.fire({
                                title: "Success!",
                                text:  "Your Payment Is Successfull",
                                type: "success",
                                timer: 3000,
                                showConfirmButton: false
                            });
                            
                        //window.setTimeout(function(){ } ,5000);
                        window.location.replace(mainUrl+'/success/'+response.order_id);
                         
                        } else {
                          alert(response.message);
                            $('.preloader').css('display','none');
                         
                            Swal.fire({
                                title: "Oops!",
                                text:  "Your Payment Is Failed",
                                type: "error",
                                timer: 3000,
                                showConfirmButton: false
                            });
                        window.setTimeout(function(){ } ,5000);
                       
                        }
                    }, 'json');
        //alert('Submit your nonce to your server here!');
      });
    }, false);
  });
});
}

}

function displayPay(){

	if($('#collect_item').css('display') == 'block'){
		if(
			$('input[name="directioncheck_collect"]').prop('checked') == true &&  
			$('input[name="returncheck_collect"]').prop('checked') == true &&
			$('input[name="slot_time"]:checked').val() != null
		){
			braintreeUI();
		//braintreeui2();
		}else{
			$(".col-md-12.payment_div").css('display','none');
		}
	}


	if($('#deliver_item').css('display') == 'block'){
		
		if($("input[name='returncheck_deliver']").prop('checked') == true ){
			braintreeUI();
			//braintreeui2();
		}else{
			$(".col-md-12.payment_div").css('display','none');
		}
	}
}



$( document ).ready(function() {

$(".checkclass").click(function() {

  //alert('called');

});


$(".collect_condition").click(function (){
		if(
			/*$('input[name="timecheck_collect"]').prop('checked') == true &&  
			*/$('input[name="directioncheck_collect"]').prop('checked') == true &&  
			$('input[name="returncheck_collect"]').prop('checked') == true &&
			($('input[name="slot_time"]:checked').val() != null || $('input[name="service_slot_time[]"]:checked').val() != null)
		){
			braintreeUI();
		//braintreeui2();
		}else{
			$(".col-md-12.payment_div").css('display','none');
		}
	});


	$(".deliver_condition").click(function (){
		
		if($(this).prop('checked') == true ){
			braintreeUI();
			//braintreeui2();
		}else{
			$(".col-md-12.payment_div").css('display','none');
		}
	});

	//color the radio button
	$('span.each_through').each(function(n) {
		var color_type = $(this).attr('data-color_type');
		$(this).find('.icheck-list').find('.custom-radio').find('div').removeClass();

		$(this).find('.icheck-list').find('.custom-radio').find('div').addClass("iradio_minimal-"+color_type);
	});

});

