$(document).ready(function(){
    var delivery_status = $('#deliverystatus').val();
    if(delivery_status=='1') {  // Means single delivery option

        var id = $('#delivery_option').val();
       var amount = parseFloat($('#amount' + id).val());
      var sellingprice = parseFloat($('input[name=purchase_val]').val());
        var totalamount = amount + sellingprice;
        //var description = $('#description' + id).val();
        if (id != '0') {
            var currencysymbol = $('input[name=currency_symbol]').val();
            $('#deliveryprice').text('+ ' + currencysymbol + amount);
            var currencysymbol = $('input[name=currency_symbol]').val();
            $('#paymentheading').text('Pay ' + currencysymbol + formatNumber(totalamount));
            //$('#deliverydescription').text('( ' + description + ')');
            $('input[name=delivery_charge]').val(amount);
        } else               // When It's 0
        {
            $('#deliveryprice').text('');
            var currencysymbol = $('input[name=currency_symbol]').val();
            $('#paymentheading').text('Pay ' + currencysymbol + formatNumber(totalamount));
            //$('#deliverydescription').text('');
            $('input[name=delivery_charge]').val('0');
        }
    }
});
function formatNumber(num) {
    var amount = num.toFixed(2);
    return amount.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
}
function discountpopup()
{
    var discount = parseInt($('#discount').val());
    var heading = discount+'% Credit Discount';
    Swal.fire({
        title: "<h1>"+heading+"</h1>",
        text:  "Pay with credit, and we'll give you "+discount+"% discount on all your purchases.",
        type: "success",
       showConfirmButton: true
    });
}
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
        lend_option_change();
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
    $('#o_subs_period').val(optionVal);
	var quantity = $("#p_quantity").val();
	var new_price_update = new_price*quantity;
    var deliverycharge = parseFloat($('input[name=delivery_charge]').val());
    var totalamount = new_price_update + deliverycharge;
    var currencysymbol = $('input[name=currency_symbol]').val();
    $('#paymentheading').text('Pay '+currencysymbol+formatNumber(totalamount));

	$("#purchase_type").find("option:selected").text("Subscribe for "+currencysymbol +new_price_update);
	$('.selectpicker').selectpicker('refresh');
	$('#purchase_val').val(new_price_update);
	$(".col-md-12.payment_div").css('display','block');
	displayPay();
}
function lend_option_change() {              // Function to update amount when lending option change
    var quantity = $("#p_quantity").val();
    var raw_price = $("input[name='lending_price']").val();
    var optionVal = $("select[name='buy_product_option']").find(':selected').val();
    if (optionVal == 0) {
        optionVal = 1;
    }
    var new_price_update = raw_price * quantity * optionVal;
    var deliveryamount = parseFloat($('input[name=delivery_charge]').val());
    var amount = parseFloat(new_price_update);
    var totalamount = amount + deliveryamount;
    var wallet_amount = $('#wallet_amount').val();
    //var deliverycharge = parseFloat($('input[name=delivery_charge]').val());
    //var totalamount = new_price_update + deliverycharge;
    var currencysymbol = $('input[name=currency_symbol]').val();
    console.log(new_price_update);
    $('#paymentheading').text('Pay ' + currencysymbol + formatNumber(totalamount));
    $('#purchase_val').val(new_price_update);
    $("#purchase_type").find("option:selected").text("Lend for " + currencysymbol + formatNumber(new_price_update));

    if ($('#collect_item').css('display') == 'block') {          // If the user opened the collection div
        if (
            $('input[name="directioncheck_collect"]').prop('checked') == true &&
            $('input[name="returncheck_collect"]').prop('checked') == true &&
            $('input[name="slot_time"]:checked').val() != null
        ) // If user selected  both three options slot time return check and direction check boxes
        {
            var product_type = parseInt($('input#product_type').val());
            var card_status = $('#card_status').val();
            if ((deliveryamount != '0' || totalamount != '0') && (card_status == '0')) {
                $('#freeorder-container').css('display', 'none');
                //$('#creditbutton').val('Pay ' + currencysymbol + totalamount 'with credit');
                $("#credit-container").css('display', 'none');
                $('#card-number').empty();
                $('#expiration-date').empty();
                $('#cvv').empty();
                braintreeUI();
            } else {
                if ((deliveryamount != '0' || totalamount != '0') && (wallet_amount < totalamount)) {
                    $('#freeorder-container').css('display', 'none');
                    //$('#creditbutton').val('Pay ' + currencysymbol + totalamount 'with credit');
                    $("#credit-container").css('display', 'none');
                    $('#card-number').empty();
                    $('#expiration-date').empty();
                    $('#cvv').empty();
                    braintreeUI();
                } else if ((totalamount != '0' || deliveryamount != '0') && wallet_amount >= totalamount) {
                    var discount = parseInt($('#discount').val());
                    var old_amount = amount;
                    var discount_amount = parseFloat((amount) * (discount / 100));
                    var new_amount = amount - discount_amount;
                    var total_amount = new_amount + deliveryamount;
                    $(".payment_div").css('display', 'block');
                    $('#card-number').empty();
                    $('#expiration-date').empty();
                    $('#cvv').empty();
                    $("#dropin-container").css('display', 'none');
                    $("#creditbutton").css('display', 'block');
                    $('#paywithcredittext').html('(' + currencysymbol + formatNumber(old_amount) + ' - <a onclick=discountpopup()>' + formatNumber(discount) + '% discount </a>' + currencysymbol + formatNumber(discount_amount) + ' = ' + currencysymbol + formatNumber(new_amount) + ' + ' + currencysymbol + formatNumber(deliveryamount) + ' = ' + currencysymbol + formatNumber(total_amount) + ')');
                    $("#creditbutton").attr('value', 'Pay ' + currencysymbol + totalamount + ' with credit');
                    $("#credit-container").css('display', 'block');
                    //$("#creditbutton").css('display','none');
                } else {
                    $(".col-md-12.payment_div").css('display', 'block');
                    $('#freeorder-container').css('display', 'block');
                    $('#credit-container').css('display', 'none');
                    $('#card-number').empty();
                    $('#expiration-date').empty();
                    $('#cvv').empty();
                }
            }
        }


    }
}

//changing quantity
function quantity_change(data){
	var quantity = $(data).val();
  var currency_symbol = $("input[name='currency_symbol']").val();

	var purchase_type = $("#purchase_type").find("option:selected").attr('data-purchange_type');
	

	if(purchase_type == 'buy_options'){

		var raw_price = $("input[name='selling_price']").val();
		console.log(raw_price);
		var new_price_update = raw_price*quantity;
        var deliverycharge = parseFloat($('input[name=delivery_charge]').val());
        var totalamount = new_price_update + deliverycharge;
        var currencysymbol = $('input[name=currency_symbol]').val();
        $('#paymentheading').text('Pay '+currencysymbol+formatNumber(totalamount));
		$('#purchase_val').val(new_price_update);
		$("#purchase_type").find("option:selected").text("Buy for "+currency_symbol+formatNumber(new_price_update));

	}else if(purchase_type == 'lending_options'){  
		var optionVal = $("select[name='buy_product_option']").find(':selected').val();
		if(optionVal == 0){
			optionVal = 1;
		}
		var raw_price = $("input[name='lending_price']").val();
		console.log(raw_price);
		var new_price_update = raw_price*quantity*optionVal;
    var deliverycharge = parseFloat($('input[name=delivery_charge]').val());
    var totalamount = new_price_update + deliverycharge;
    var currencysymbol = $('input[name=currency_symbol]').val();
    $('#paymentheading').text('Pay '+currencysymbol+formatNumber(totalamount));
		$('#purchase_val').val(new_price_update);
		$("#purchase_type").find("option:selected").text("Lend for "+currency_symbol+formatNumber(new_price_update));

	}else if(purchase_type == 'service_options'){
    if(quantity>'1')
    {
    $('#credit-container').css('display','none');
    $('#freeorder-container').css('display','none');
    $("#credit-container").css('display','none');
    $("#creditbutton").css('display','none');
    $('#card-number').empty();
    $('#expiration-date').empty();
    $('#cvv').empty();
    $('input[name="returncheck_collect"]').prop('checked',false);
    $('input[name="directioncheck_collect"]').prop('checked',false);
    $('#dropin-container').css('display','none');
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
    console.log(raw_price);
		var new_price_update = quantity*raw_price;
    var new_service_time = quantity*service_time;
    $('#purchase_val').val(new_price_update);
		$("#purchase_type").find("option:selected").text("Buy for "+currency_symbol+formatNumber(new_price_update)+'/'+new_service_time+''+service_time_type);

	}else{
		var optionVal = $("select[name='buy_product_option']").find(':selected').val();
		if(optionVal == 0){
			optionVal = 1;
		}
		var raw_price = $("input[name='subscribe_price']").val();
		var new_price_update = raw_price*quantity*optionVal;
    var deliverycharge = parseFloat($('input[name=delivery_charge]').val());
    var totalamount = new_price_update + deliverycharge;
    var currencysymbol = $('input[name=currency_symbol]').val();
    $('#paymentheading').text('Pay '+currencysymbol+formatNumber(totalamount));
		$('#purchase_val').val(new_price_update);
		$("#purchase_type").find("option:selected").text("Subscribe for "+currency_symbol+formatNumber(new_price_update));
	}

	$('.selectpicker').selectpicker('refresh');

	/* Code for updating price in pay with credit button as well as braintree heading */
    var amount = parseFloat($('#purchase_val').val());
    var deliveryamount = parseFloat($('input[name=delivery_charge]').val());
    var wallet_amount = parseFloat($('#wallet_amount').val());
    var totalamount = amount + deliveryamount;
    var currencysymbol = $('input[name=currency_symbol]').val();
    if($('#collect_item').css('display') == 'block') {          // If the user opened the collection div
        if (
            $('input[name="directioncheck_collect"]').prop('checked') == true &&
            $('input[name="returncheck_collect"]').prop('checked') == true &&
            $('input[name="slot_time"]:checked').val() != null
        ) // If user selected  both three options slot time return check and direction check boxes
        {
            var product_type = parseInt($('input#product_type').val());
            var card_status = $('#card_status').val();
            if((deliveryamount!='0' || totalamount!='0') && (card_status=='0'))
            {
                $('#freeorder-container').css('display','none');
                //$('#creditbutton').val('Pay ' + currencysymbol + totalamount 'with credit');
                $("#credit-container").css('display','none');
                $('#card-number').empty();
                $('#expiration-date').empty();
                $('#cvv').empty();
                braintreeUI();
            }
            else {
                if ((deliveryamount != '0' || totalamount != '0') && (wallet_amount < totalamount)) {

                    $('#freeorder-container').css('display', 'none');
                    //$('#creditbutton').val('Pay ' + currencysymbol + totalamount 'with credit');
                    $("#credit-container").css('display', 'none');
                    $('#card-number').empty();
                    $('#expiration-date').empty();
                    $('#cvv').empty();
                    braintreeUI();
                } else if ((totalamount != '0' || deliveryamount != '0') && wallet_amount >= totalamount) {
                    var discount = parseInt($('#discount').val());
                    var old_amount = amount;
                    var discount_amount = parseFloat((amount) * (discount / 100));
                    var new_amount = amount - discount_amount;
                    var total_amount = new_amount + deliveryamount;

                    $(".payment_div").css('display', 'block');
                    $('#card-number').empty();
                    $('#expiration-date').empty();
                    $('#cvv').empty();
                    $("#dropin-container").css('display', 'none');
                    $("#creditbutton").css('display', 'block');
                    $('#paywithcredittext').html('(' + currencysymbol + formatNumber(old_amount) + ' - <a onclick=discountpopup()>' + formatNumber(discount) + '% discount </a>' + currencysymbol + formatNumber(discount_amount) + ' = ' + currencysymbol + formatNumber(new_amount) + ' + ' + currencysymbol + formatNumber(deliveryamount) + ' = ' + currencysymbol + formatNumber(total_amount) + ')');
                    $("#creditbutton").attr('value', 'Pay ' + currencysymbol + formatNumber(totalamount) + ' with credit');
                    $("#credit-container").css('display', 'block');
                    $('#freeorder-container').css('display', 'none');
                    //$("#creditbutton").css('display','none');
                } else {
                    $(".col-md-12.payment_div").css('display', 'block');
                    $('#freeorder-container').css('display', 'block');
                    $('#credit-container').css('display', 'none');
                    $('#card-number').empty();
                    $('#expiration-date').empty();
                    $('#cvv').empty();
                }
            }
        }
    }
    if($('#deliver_item').css('display') == 'block'){                           // If the delivery div is selected
        if($("input[name='returncheck_deliver']").prop('checked') == true ){    // If money back gurantee checkbox is selected
            var product_type = parseInt($('input#product_type').val());
            var card_status = $('#card_status').val();
            if((deliveryamount!='0' || totalamount!='0') && (card_status=='0'))
            {
                $('#freeorder-container').css('display','none');
                //$('#creditbutton').val('Pay ' + currencysymbol + totalamount 'with credit');
                $("#credit-container").css('display','none');
                $("#creditbutton").css('display','none');
                $('#card-number').empty();
                $('#expiration-date').empty();
                $('#cvv').empty();
                braintreeUI();
            }
            else {
                if ((deliveryamount != '0' || totalamount != '0') && (wallet_amount < totalamount)) {
                    $('#freeorder-container').css('display', 'none');
                    //$('#creditbutton').val('Pay ' + currencysymbol + totalamount 'with credit');
                    $("#credit-container").css('display', 'none');
                    $("#creditbutton").css('display', 'none');
                    $('#card-number').empty();
                    $('#expiration-date').empty();
                    $('#cvv').empty();
                    braintreeUI();
                } else if ((totalamount != '0' || deliveryamount != '0') && wallet_amount >= totalamount) {
                    //console.log('here');
                    var discount = parseInt($('#discount').val());
                    var old_amount = amount;
                    var discount_amount = parseFloat((amount) * (discount / 100));
                    var new_amount = amount - discount_amount;
                    var total_amount = new_amount + deliveryamount;
                    $(".payment_div").css('display', 'block');
                    $('#card-number').empty();
                    $('#expiration-date').empty();
                    $('#cvv').empty();
                    $("#dropin-container").css('display', 'none');
                    $("#creditbutton").css('display', 'block');
                    $('#paywithcredittext').html('(' + currencysymbol + formatNumber(old_amount) + ' - <a onclick=discountpopup()>' + formatNumber(discount) + '% discount </a>' + currencysymbol + formatNumber(discount_amount) + ' = ' + currencysymbol + formatNumber(new_amount) + ' + ' + currencysymbol + formatNumber(deliveryamount) + ' = ' + currencysymbol + formatNumber(total_amount) + ')');
                    $("#creditbutton").attr('value', 'Pay ' + currencysymbol + formatNumber(totalamount) + ' with credit');
                    $("#credit-container").css('display', 'block');
                    //$("#creditbutton").css('display','none');
                } else {
                    $(".col-md-12.payment_div").css('display', 'block');
                    $('#freeorder-container').css('display', 'block');
                    $('#credit-container').css('display', 'none');
                    $('#card-number').empty();
                    $('#expiration-date').empty();
                    $('#cvv').empty();
                }
            }
        }

    }
}
function hidecollectitem()
{
    $('#buttons').css('display','flex');
    $('#collect_item').css('display','none');
    $('.payment_div').css('display','none');
    $('.radioselect').prop('checked',false);

}
function hidedeliveritem()

{
    $('#buttons').css('display','flex');
    $('#deliver_item').css('display','none');
    $('.payment_div').css('display','none');
    $('#customCheck5').prop('checked',false);
}

//choosing collection type
function collection_type(data){
  var amount = parseFloat(document.querySelector('#purchase_val').value);
  var wallet_amount = parseFloat(document.querySelector('#wallet_amount').value);
  var delivery_charge = parseFloat($('input[name="delivery_charge"]').val());
	$('#buttons').css('display','none');
	var show_col_type = $(data).attr('data-show_coll_type');
	$("#"+show_col_type).css('display','block');
	var collection_type = $(data).attr('data-hide_coll_type');
	$("#"+collection_type).css('display','none');
  //alert(show_col_type);
  if(show_col_type=='deliver_item')
  {
      $('#paywithcredittext').text('');
    //$('.col-md-12.payment_div').css('display','block'); 
    //$('input[name="slot_time"]').prop('checked', false);
    //$('input[name="timecheck_collect"]').prop('checked', false);
    //$('input[name="directioncheck_collect"]').prop('checked', false);
    //$('input[name="returncheck_collect"]').prop('checked', false);
      $('.iradio_minimal-blue').removeClass('checked');
    var deliveryoptioncheck = $('#delivery_option option:selected').val();
    var delivery_status = $('#deliverystatus').val();

    if((deliveryoptioncheck!='0' || delivery_status=='1') && (deliveryoptioncheck=='' && delivery_status==''))   // If user select the delivery option then show payment div
    { //$('input[name="returncheck_deliver"]').prop('checked', true);
      if(delivery_status=='1')   // if there is only one delivery option
      {
          var id = $('#delivery_option').val();
          var delivery_charge = parseFloat($('#amount'+id).val());
      }
      else {
          var id = $('#delivery_option option:selected').val();
          var description = $('#description'+id).val();
          $('#deliverydescription').text('( '+description+ ')');
      }
      var deliveryamount = parseFloat($('#amount'+id).val());

      var totalamount = amount + delivery_charge;
      var currencysymbol = $('input[name=currency_symbol]').val();
      /*$('#deliveryprice').text('+ '+currencysymbol+amount);*/
        $('#deliveryprice').text('+ '+currencysymbol+delivery_charge);
      var currencysymbol = $('input[name=currency_symbol]').val();
      $('#paymentheading').text('Pay '+currencysymbol+formatNumber(totalamount));
      $('input[name="delivery_charge"]').val(deliveryamount);
        var product_type = parseInt($('input#product_type').val());
        var card_status = $('#card_status').val();
        if((deliveryamount!='0' || totalamount!='0') && (card_status=='0'))
        {
            $('#freeorder-container').css('display','none');
            //$('#creditbutton').val('Pay ' + currencysymbol + totalamount 'with credit');
            $("#credit-container").css('display','none');

            //braintreeUI();
        }
        else {
            if ((deliveryamount != '0' || totalamount != '0') && (wallet_amount < totalamount)) {
                $('#freeorder-container').css('display', 'none');
                //$('#creditbutton').val('Pay ' + currencysymbol + totalamount 'with credit');
                $("#credit-container").css('display', 'none');

                //braintreeUI();
            } else if ((amount != '0' || deliveryamount != '0') && wallet_amount >= totalamount) {
                $(".payment_div").css('display', 'block');
                $('#card-number').empty();
                $('#expiration-date').empty();
                $('#cvv').empty();
                $("#creditbutton").attr('value', 'Pay ' + currencysymbol + totalamount + ' with credit');
                $("#credit-container").css('display', 'block');
                $("#creditbutton").css('display', 'none');
            } else {
                $(".col-md-12.payment_div").css('display', 'block');
                $('#freeorder-container').css('display', 'block');
                $('#credit-container').css('display', 'none');
                $('#card-number').empty();
                $('#expiration-date').empty();
                $('#cvv').empty();

            }
        }
    }
    else
    {
        $('input[name="delivery_charge"]').val('0');
        var currencysymbol = $('input[name=currency_symbol]').val();
        $('#paymentheading').text('Pay '+currencysymbol+formatNumber(amount));


    }

  }
  else
  {
      $('#paywithcredittext').text('');
    $(".col-md-12.payment_div").css('display','none');
    $('input[name="returncheck_deliver"]').prop('checked', false); 
    //radio and checkboxes
    $('input[name="slot_time"]').prop('checked', false);
    /*$('input[name="timecheck_collect"]').prop('checked', false);
    */$('input[name="directioncheck_collect"]').prop('checked', false);
    $('input[name="returncheck_collect"]').prop('checked', false); 
    $('input[name="delivery_charge"]').val('0');
    //$('#delivery_option').val('0');
      //alert('here');
      var delivery_status = $('#deliverystatus').val();
      if(delivery_status!='1') {
          $("#delivery_option").val('0');

      }
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
$('#dropin-container').css('display','block');
var form = document.querySelector('#my-sample-form');
var submit = document.querySelector('input[type="submit"]');
var mainUrl = $("#weburl").val();
var authorization_token = $("input[name='initial_token']").val();
var clientToken = document.querySelector("#clientToken").value;
var braintree_id = document.querySelector("#braintree_id").value;
var amount = parseFloat(document.querySelector('#purchase_val').value);
var currency_rate = $('#currency_rate').val();
var deliverycharge = parseFloat($('input[name="delivery_charge"]').val());
var delivery_status = $('#deliverystatus').val();
if(delivery_status=='1')        // if there is only one delivery option
{
    var delivery_provider = $('#delivery_option').val();
}
else {
    var delivery_provider = $('#delivery_option option:selected').val();
}
var totalamount = amount + deliverycharge;
var def_gbp_amount = Math.round(amount / currency_rate);
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
        $.get(mainUrl+'/payment/process', {payload,totalamount,braintree_id,product_id,delivery_provider,formData,def_gbp_amount}, function (response) {
                       // console.log(response.success);
                        //console.log(formData);
                        if (response.success==1) {
                          $('.preloader').css('display','block');
                           Swal.fire({
                                title: "Success!",
                                text:  "Your Payment is Successfull",
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
                                text:  "Your Payment is Failed",
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
        $.get(mainUrl+'/payment/process', {payload,totalamount,braintree_id,product_id,delivery_provider,formData,def_gbp_amount}, function (response) {
                        //console.log(response.order_id);
                       // console.log(response);
                      //  console.log(formData);
                       if (response.success==1) {
                           $('.preloader').css('display','block');
                           Swal.fire({
                                title: "Success!",
                                text:  "Your Payment is Successfull",
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
                                text:  "Your Payment is Failed",
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
function freeorder()        // if amount is zero then call this function
{
  var formData = $( "#add_to_cart" ).serialize();
  var mainUrl = $("#weburl").val();
    $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
    $.ajax({
        url: mainUrl+"/freeorder",
        type: 'POST',              
        data: formData,
        dataType:"json",
        success: function(data)
        {
            if(data.success == 0){ //error
                    Swal.fire("Error",data.message,"warning");
                }  
            if(data.success == 1){ //Order Placed
            	
              window.location.replace(mainUrl+'/success/'+data.order_id);
            }
        },
        error: function(data)
        {
            Swal.fire('Error occured while inserting data');
        }
    });
}
function paywithcredit()        // if user have credit balance greater than item price
{
    var formData = $( "#add_to_cart" ).serialize();
    var mainUrl = $("#weburl").val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: mainUrl+"/paywithcredit",
        type: 'POST',
        data: formData,
        dataType:"json",
        success: function(data)
        {
            if(data.success == 0){ //error
                Swal.fire("Error",data.message,"warning");
            }
            if(data.success == 1){ //Order Placed
               window.location.replace(mainUrl+'/success/'+data.order_id+'/'+data.discount);
            }
        },
        error: function(data)
        {
            Swal.fire('Error occured while inserting data');
        }
    });
}

function displayPay(){
  var currencysymbol = $('input[name=currency_symbol]').val();
  var amount = parseFloat(document.querySelector('#purchase_val').value);
  var delivery_amount = parseFloat($('input[name="delivery_charge"]').val());
  var total_amount = amount + delivery_amount;
  //alert(delivery_amount);
  var wallet_amount = parseFloat(document.querySelector('#wallet_amount').value);
	if($('#collect_item').css('display') == 'block'){
        var product_type = parseInt($('input#product_type').val());
        var card_status = $('#card_status').val();
	   if(
			$('input[name="directioncheck_collect"]').prop('checked') == true &&  
			$('input[name="returncheck_collect"]').prop('checked') == true &&
			$('input[name="slot_time"]:checked').val() != null
		){
	  if((total_amount!='0') && (card_status=='0'))
      {
          $('#credit-container').css('display','none');
          $('#card-number').empty();
          $('#expiration-date').empty();
          $('#cvv').empty();
          braintreeUI();
      }
	  else {
          if ((total_amount != '0') && (wallet_amount < totalamount)) {
              $('#credit-container').css('display', 'none');
              $('#card-number').empty();
              $('#expiration-date').empty();
              $('#cvv').empty();
              braintreeUI();
          } else if (total_amount != '0' && wallet_amount >= total_amount) {
              var discount = parseInt($('#discount').val());
              var old_amount = amount;
              var discount_amount = parseFloat((amount) * (discount / 100));
              var new_amount = amount - discount_amount;
              var total_amount = new_amount + delivery_amount;
              $('.payment_div').css('display', 'block');
              $('#card-number').empty();
              $('#expiration-date').empty();
              $('#cvv').empty();
              $('#dropin-container').css('display', 'none');
              // $('#creditbutton').val('Pay ' + currencysymbol + amount 'with credit');
              $('#credit-container').css('display', 'block');
              $('#creditbutton').css('display', 'block');
              //console.log(amount);
              //alert(amount);
              $('#paywithcredittext').html('(' + currencysymbol + formatNumber(old_amount) + ' - <a onclick=discountpopup()>' + formatNumber(discount) + '% discount </a>' + currencysymbol + formatNumber(discount_amount) + ' = ' + currencysymbol + formatNumber(new_amount) + ' + ' + currencysymbol + formatNumber(delivery_amount) + ' = ' + currencysymbol + formatNumber(total_amount) + ')');
              $('#creditbutton').attr('value', 'Pay ' + currencysymbol + formatNumber(total_amount) + ' with credit');
          } else {
              $('#dropin-container').css('display', 'none');
              $('.payment_div').css('display', 'block');
              $("#freeorder-container").css('display', 'block');
              $('#credit-container').css('display', 'none');
              $('#card-number').empty();
              $('#expiration-date').empty();
              $('#cvv').empty();
          }
      }
    //braintreeui2();
		}else{
			$(".col-md-12.payment_div").css('display','none');
      $('#freeorder-container').css('display','none');
      $('#dropin-container').css('display','none');
      $('#credit-container').css('display','none');
		}
	}


	if($('#deliver_item').css('display') == 'block'){
        var product_type = parseInt($('input#product_type').val());
        var card_status = $('#card_status').val();
		if($("input[name='returncheck_deliver']").prop('checked') == true ){
		    if((total_amount!='0') && (card_status=='0'))
            {
                $('#credit-container').css('display','none');
                $('#card-number').empty();
                $('#expiration-date').empty();
                $('#cvv').empty();
                braintreeUI();
            }
		    else {
                if ((total_amount != '0') && (wallet_amount < totalamount)) {
                    $('#credit-container').css('display', 'none');
                    $('#card-number').empty();
                    $('#expiration-date').empty();
                    $('#cvv').empty();
                    braintreeUI();
                } else if (total_amount != '0' && wallet_amount >= total_amount) {
                    var discount = parseInt($('#discount').val());
                    var old_amount = amount;
                    var discount_amount = parseFloat((amount) * (discount / 100));
                    var new_amount = amount - discount_amount;
                    var total_amount = new_amount + delivery_amount;
                    $('.payment_div').css('display', 'block');
                    $('#card-number').empty();
                    $('#expiration-date').empty();
                    $('#cvv').empty();
                    $('#dropin-container').css('display', 'none');
                    // $('#creditbutton').val('Pay ' + currencysymbol + amount 'with credit');
                    $('#credit-container').css('display', 'block');
                    $('#creditbutton').css('display', 'block');
                    //console.log(amount);
                    //alert(amount);
                    $('#paywithcredittext').html('(' + currencysymbol + formatNumber(old_amount) + ' - <a onclick=discountpopup()>' + formatNumber(discount) + '% discount </a>' + currencysymbol + formatNumber(discount_amount) + ' = ' + currencysymbol + formatNumber(new_amount) + ' + ' + currencysymbol + formatNumber(delivery_amount) + ' = ' + currencysymbol + formatNumber(total_amount) + ')');
                    $('#creditbutton').attr('value', 'Pay ' + currencysymbol + formatNumber(total_amount) + ' with credit');
                } else {
                    $('#dropin-container').css('display', 'none');
                    $('.payment_div').css('display', 'block');
                    $("#freeorder-container").css('display', 'block');
                    $('#credit-container').css('display', 'none');
                    $('#card-number').empty();
                    $('#expiration-date').empty();
                    $('#cvv').empty();
                }
            }
      //braintreeui2();
		}else{
			$(".col-md-12.payment_div").css('display','none');
      $('#freeorder-container').css('display','none');
      $('#dropin-container').css('display','none');
      $('#credit-container').css('display','none');
		}
	}
}



$( document ).ready(function() {

$(".checkclass").click(function() {

  //alert('called');

});


$(".collect_condition").click(function (){
    var currencysymbol = $('input[name=currency_symbol]').val();
    var deliveryamount = parseFloat($('input[name="delivery_charge"]').val());
  var amount = parseFloat(document.querySelector('#purchase_val').value);
  var wallet_amount = parseFloat(document.querySelector('#wallet_amount').value);
    var totalamount = deliveryamount + amount;
		if(
			/*$('input[name="timecheck_collect"]').prop('checked') == true &&  
			*/$('input[name="directioncheck_collect"]').prop('checked') == true &&  
			$('input[name="returncheck_collect"]').prop('checked') == true &&
			($('input[name="slot_time"]:checked').val() != null || $('input[name="service_slot_time[]"]:checked').val() != null)
		){
            var product_type = parseInt($('input#product_type').val());
            var card_status = $('#card_status').val();
            if(totalamount!='0' && card_status=='0')
            {
                var currencysymbol = $('input[name=currency_symbol]').val();
                $('#paymentheading').text('Pay '+currencysymbol+formatNumber(amount));
                $('#card-number').empty();
                $('#expiration-date').empty();
                $('#cvv').empty();
                braintreeUI();
            }
            else {
                if ((totalamount != '0') && (wallet_amount < totalamount)) {
                    var currencysymbol = $('input[name=currency_symbol]').val();
                    $('#paymentheading').text('Pay ' + currencysymbol + formatNumber(amount));
                    $('#card-number').empty();
                    $('#expiration-date').empty();
                    $('#cvv').empty();
                    braintreeUI();
                } else if (totalamount != '0' && wallet_amount >= totalamount) {
                    var discount = parseInt($('#discount').val());
                    var old_amount = amount;
                    var discount_amount = parseFloat((amount) * (discount / 100));
                    var new_amount = amount - discount_amount;
                    var total_amount = new_amount + deliveryamount;
                    $('.payment_div').css('display', 'block');
                    $('#credit-container').css('display', 'block');
                    $("#creditbutton").css('display', 'block');
                    $('#paywithcredittext').html('(' + currencysymbol + formatNumber(old_amount) + ' - <a onclick=discountpopup()>' + formatNumber(discount) + '% discount </a>' + currencysymbol + formatNumber(discount_amount) + ' = ' + currencysymbol + formatNumber(new_amount) + ' + ' + currencysymbol + formatNumber(deliveryamount) + ' = ' + currencysymbol + formatNumber(total_amount) + ')');
                    $('#creditbutton').attr('value', 'Pay ' + currencysymbol + formatNumber(total_amount) + ' with credit');
                } else {
                    $('.payment_div').css('display', 'block');
                    $('#dropin-container').css('display', 'none');
                    $("#freeorder-container").css('display', 'block');
                    $('#credit-container').css('display', 'none');
                    $('#card-number').empty();
                    $('#expiration-date').empty();
                    $('#cvv').empty();

                }
            }
    //braintreeui2();
		}else{
			$(".col-md-12.payment_div").css('display','none');
      $('#freeorder-container').css('display','none');
      $('#dropin-container').css('display','none');
      $('#credit-container').css('display','none');
		}
	});


	$(".deliver_condition").click(function (){
	    var currencysymbol = $('input[name=currency_symbol]').val();
        var deliveryamount = parseFloat($('input[name="delivery_charge"]').val());
	    var amount = parseFloat(document.querySelector('#purchase_val').value);
	    var totalamount = deliveryamount + amount;
		var wallet_amount = parseFloat(document.querySelector('#wallet_amount').value);
		var delivery_status = $('#deliverystatus').val();
		if(delivery_status=='1')        // if there is only one delivery option
        {
            var deliveryoptioncheck = $('#delivery_option').val();
        }
		else
		{
            var deliveryoptioncheck = $('#delivery_option option:selected').val();
        }
             var purchase_type = $("#purchase_type").find("option:selected").attr('data-purchange_type');
    var purchase_type_val = $('#purchase_type').val();
    if(deliveryoptioncheck!='0')   // If user select the delivery option then show payment div
    {
      var deliveryamount = parseFloat($('input[name="delivery_charge"]').val());
     	if($(this).prop('checked') == true )  // If user checked 30 day money back gurantee
      {
          if(purchase_type=='subscription_options' && $("#subscriptionoption option:selected").val()=='0')
          {
              swal('warning','Please select subscription time');
              $(this).prop('checked',false);
          }
          else
          {
              var product_type = parseInt($('input#product_type').val());
              var card_status = $('#card_status').val();
              if((totalamount!='0' || deliveryamount!='0') && (card_status=='0'))
              {
                  $('#freeorder-container').css('display','none');
                  $('#card-number').empty();
                  $('#expiration-date').empty();
                  $('#cvv').empty();
                  braintreeUI();
              }
              else {
                  if ((totalamount != '0' || deliveryamount != '0') && (wallet_amount < totalamount)) {
                      $('#freeorder-container').css('display', 'none');
                      $('#card-number').empty();
                      $('#expiration-date').empty();
                      $('#cvv').empty();
                      braintreeUI();
                  } else if ((totalamount != '0' || deliveryamount != '0') && wallet_amount >= totalamount) {
                      var discount = parseInt($('#discount').val());
                      var old_amount = amount;
                      var discount_amount = parseFloat((amount) * (discount / 100));
                      var new_amount = amount - discount_amount;
                      var total_amount = new_amount + deliveryamount;
                      $(".payment_div").css('display', 'block');
                      $("#credit-container").css('display', 'block');
                      $("#creditbutton").css('display', 'block');
                      $('#paywithcredittext').html('(' + currencysymbol + formatNumber(old_amount) + ' - <a onclick=discountpopup()>' + formatNumber(discount) + '% discount </a>' + currencysymbol + formatNumber(discount_amount) + ' = ' + currencysymbol + formatNumber(new_amount) + ' + ' + currencysymbol + formatNumber(deliveryamount) + ' = ' + currencysymbol + formatNumber(total_amount) + ')');
                      $('#creditbutton').attr('value', 'Pay ' + currencysymbol + formatNumber(total_amount) + ' with credit');
                  } else {
                      //$("#freeorder-container").css('display','none');
                      $(".payment_div").css('display', 'block');
                      $('#dropin-container').css('display', 'none');
                      $("#freeorder-container").css('display', 'block');
                      $('#credit-container').css('display', 'none');
                      $('#card-number').empty();
                      $('#expiration-date').empty();
                      $('#cvv').empty();

                  }
              }
          }
        //braintreeui2();
  		}
      else
      {
          $(".col-md-12.payment_div").css('display','none');
          $('#freeorder-container').css('display','none');
          $('#dropin-container').css('display','none');
          $('#credit-container').css('display','none');
      }
    }

    else
    {
      swal('warning','Please select delivery option');
      $(this).prop('checked',false);
    }
	});
	//color the radio button
	$('span.each_through').each(function(n) {
		var color_type = $(this).attr('data-color_type');
		$(this).find('.icheck-list').find('.custom-radio').find('div').removeClass();

		$(this).find('.icheck-list').find('.custom-radio').find('div').addClass("iradio_minimal-"+color_type);
	});

});
function updatedeliveryoption()   // If user clicks deliver it
  {
      var id = $('#delivery_option option:selected').val();
      var amount = parseFloat($('#amount'+id).val());
      var sellingprice = parseFloat($('input[name=purchase_val]').val());
      var totalamount = amount + sellingprice;
      var description = $('#description'+id).val();
      if(id!='0')
      {
        var currencysymbol = $('input[name=currency_symbol]').val();
        $('#deliveryprice').text('+ '+currencysymbol+amount);
        var currencysymbol = $('input[name=currency_symbol]').val();
        $('#paymentheading').text('Pay '+currencysymbol+formatNumber(totalamount));
        $('#deliverydescription').text('( '+description+ ')');
        $('input[name=delivery_charge]').val(amount);
      }
      else               // When It's 0
      {
        $('#deliveryprice').text('');
        var currencysymbol = $('input[name=currency_symbol]').val();
        $('#paymentheading').text('Pay '+currencysymbol+formatNumber(totalamount));
        $('#deliverydescription').text('');
        $('input[name=delivery_charge]').val('0');
      }
  }


