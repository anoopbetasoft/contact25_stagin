<script type="text/javascript">// JavaScript Document

$(document).ready(function(){
	
	
	$('#customer_login').click(function(){
		var email = $('#email_login').val();
		var password = $('#password_login').val();

		/*
		$.ajax({
                    url: "customer_login.php",
                    data: {
							s_id:s_id
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("we tried to add the side menu, but you have no internet connection");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {

						$('.content').html(data);
						
						// nothing - it's just saving a session var in the ebay_processing file //
                    }

        });*/
	});
	
	$('#buy_now').click(function(){
		
		alert("test");
	});
	
	
	$('#buy_now_ready').click(function(){
		
		$('#buy_now').html('connecting to PayPal... please wait');
		$('#sell_now').html('');
		send_to_paypal.submit();
	});
	
	
	
/*
$('.display_product').click(function(){
		
		var s_id = $(this).data('prod-id');
		alert(s_id);
		var s_id = 1;
	
		$.ajax({
                    url: "http://contact25.com/js/ajax/display_product.php",
                    data: {
							s_id:s_id
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("we tried to add the side menu, but you have no internet connection");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {

						$('.content').html(data);
						
						// nothing - it's just saving a session var in the ebay_processing file //
                    }

        });
	});
	
*/
		


	
});</script><?php 

include('../../config.php');  
include('include/includes.php'); 

/*

insterprest the following posted vars


*/



$sql = 'SELECT 
			* 
		FROM 
			spares
		WHERE 
			spares.s_id = "'.$_POST['s_id'].'"
			
		';
		
		
$query		= mysql_query($sql);
$row		= mysql_fetch_assoc($query);
$num_rows	= mysql_num_rows($query);
if ($num_rows >0){
	do{
		/*
			<div style="position: relative;display: block;left:0;top:0;">
						<span style="position: absolute;z-index: 2;left:50px;top:50px;"><img src="http://www.james-hare.com/images/new_banner.png"></span>
						<span id="new_overlay_bottom"><img src="http://contact25.com/uploads/7_'.$row['s_id'].'.jpg" alt="'.$row['s_label'].'" style="max-width:800px; margin-left: auto;
    margin-right: auto;">
	<div style="max-width:1000px;"></span>
					</div>
		
		
		*/
		$price = 	$row['s_price'];
		$result .= '
		
	
		
		<div style=""><img src="http://contact25.com/uploads/7_'.$row['s_id'].'.jpg" alt="'.$row['s_label'].'" class="image_resize_mobile" style="max-width:800px;margin: 0 auto; "></div>
	
	
	<div style="max-width:1000px;margin-left: auto;
    margin-right: auto;">
					<div style="color:#fcb040; font-size:24px; line-height:30px; font-weight:bold; padding-top:30px; ">
			 		'.$row['s_label'].'</div>
					<div style="color:#056839; font-size:14px; margin-top:10px;">
			 		'.nl2br($row['s_desc']).'</div>
					<div style="clear:both;"></div>
					<div style="color:#ec008c; font-size:34px; height: 40px; padding-top: 10px; ">
			 		£'.$price.'<span style="font-size:10px;font-weight:bold;color:black">(FREE UK DELIVERY)</span></div>
					<div style="clear:both;"</div>
					
					
				

<form method="post" name="send_to_paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_cart">
<input type="hidden" name="currency_code" value="GBP">
<input type="hidden" name="upload" value="1">
<input type="hidden" name="business" value="antony.vila@jukeboxmarketing.com">

<!-- Address Details -->
<!-- Set variables that override the address stored with PayPal. -->
    <input type="hidden" name="first_name" value="">
    <input type="hidden" name="last_name" value="">
    <input type="hidden" name="address1" value="">
    <input type="hidden" name="city" value="">
     
    <input type="hidden" name="zip" value="">
    <input type="hidden" name="country" value="GB">


	
				<input type="hidden" name="item_name_1'.$i.'" value="'.addslashes($row['s_label']).' ('.addslashes($row['s_id']).')">
 				<input type="hidden" name="amount_1'.$i.'" value="'.number_format($row['s_price'], 2, '.', '').'">';
 

 
  
	 ?>
 


 <?php 
$country_dropdown = '
<select  id="u_country" name="u_country"  style="margin-top:5px; padding:5px; color:#999">
	<option value=15>United Kingdom</option>';
    
	$sql = 'SELECT * FROM countries WHERE (NOT(c_id = 15)) ORDER BY c_name ASC ';
	$query = mysql_query($sql);
	$row = mysql_fetch_assoc($query);
	$num_rows = mysql_num_rows($query);
	if ($num_rows>0){
		do{
			$country_dropdown .= '<option value="'.$row['c_id'].'">'.$row['c_name'].'</option>';
		}while($row = mysql_fetch_assoc($query));
	}
	
$country_dropdown .='</select>';

 $result .= '<input type="hidden" name="shipping_1" value="0'.$total_shipping.'">




</form>
					
					<div id="buy_now_ready"><button style="margin-top:10px; width:100%; min-height: 80px; background-color:#ed1c24; font-size:24px;" class="button button-red" >Buy Now (£'.$price.')</button></div>
					
					
					<div class="content"  style="display:none;">
        	<div style="padding:10px;" ></div>
        	<div class="one-half-responsive">
                <h4>Login (EXISTING CUSTOMERS)</h4>
                <p>We will keep you logged in on this device. <strong>If you are on a shared computer, remember to log out when you\'re done!</strong></p>
                <div class="container no-bottom" >
                    <div class="contact-form no-bottom"> 
                        <div class="formSuccessMessageWrap" id="formSuccessMessageWrap" style="overflow: hidden; display: none;">
                            <div class="big-notification green-notification">
                                <h3 class="uppercase">Message Sent </h3>
                                <a href="#" class="close-big-notification">x</a>
                                <p>Your message has been successfuly sent. Please allow up to 48 hours for a reply! Thank you!</p>
                            </div>
                        </div>
                    
                        
                            <fieldset>
                                <div class="formValidationError" id="contactNameFieldError" style="display: none;">
                                    <div class="static-notification-red tap-dismiss-notification">
                                        <p class="center-text uppercase">Name is required!</p>
                                    </div>
                                </div>             
                                <div class="formValidationError" id="contactEmailFieldError" style="display: none;">
                                    <div class="static-notification-red tap-dismiss-notification">
                                        <p class="center-text uppercase">Mail address required!</p>
                                    </div>
                                </div> 
                                <div class="formValidationError" id="contactEmailFieldError2" style="display: none;">
                                    <div class="static-notification-red tap-dismiss-notification">
                                        <p class="center-text uppercase">Mail address must be valid!</p>
                                    </div>
                                </div> 
                                <div class="formValidationError" id="contactMessageTextareaError" style="display: none;">
                                    <div class="static-notification-red tap-dismiss-notification">
                                        <p class="center-text uppercase">Message field is empty!</p>
                                    </div>
                                </div>   
                                <div class="formFieldWrap">
                                    <label class="field-title contactNameField" for="contactNameField">Email:<span>(required)</span></label>
                                    <input type="text" name="contactNameField" value="" class="contactField requiredField" id="email_login">
                                </div>
                                <div class="formFieldWrap">
                                    <label class="field-title contactEmailField" for="contactEmailField">Password: <span>(required)</span></label>
                                    <input type="password" name="contactEmailField" value="" class="contactField requiredField requiredEmailField" id="password_login">
                                </div>
                               
                                <div class="formSubmitButtonErrorsWrap">
                                    <input type="submit" class="buttonWrap button button-green customer_login" id="customer_login" value="LOGIN">
                                </div>
                            </fieldset>
                              
                    </div>
                </div>
            </div>
            <div class="decoration hide-if-responsive"></div>
            <div class="one-half-responsive last-column">
                <div class="container no-bottom">
                    <h4>Register (NEW CUSTOMERS)</h4>
                <p>Welcome! Register now and you\'ll be able to buy and sell on Contact25.  For an even better experience, don\'t forget to download our FREE APP.</p>
                <div class="container no-bottom">
                    <div class="contact-form no-bottom"> 
                        <div class="formSuccessMessageWrap" id="formSuccessMessageWrap" style="overflow: hidden; display: none;">
                            <div class="big-notification green-notification">
                                <h3 class="uppercase">Message Sent </h3>
                                <a href="#" class="close-big-notification">x</a>
                                <p>Your message has been successfuly sent. Please allow up to 48 hours for a reply! Thank you!</p>
                            </div>
                        </div>
                    
                        <form action="#" method="post" class="contactForm" id="contactForm">
                            <fieldset>
                                <div class="formValidationError" id="contactNameFieldError" style="display: none;">
                                    <div class="static-notification-red tap-dismiss-notification">
                                        <p class="center-text uppercase">Name is required!</p>
                                    </div>
                                </div>             
                                <div class="formValidationError" id="contactEmailFieldError" style="display: none;">
                                    <div class="static-notification-red tap-dismiss-notification">
                                        <p class="center-text uppercase">Mail address required!</p>
                                    </div>
                                </div> 
                                <div class="formValidationError" id="contactEmailFieldError2" style="display: none;">
                                    <div class="static-notification-red tap-dismiss-notification">
                                        <p class="center-text uppercase">Mail address must be valid!</p>
                                    </div>
                                </div> 
                                <div class="formValidationError" id="contactMessageTextareaError" style="display: none;">
                                    <div class="static-notification-red tap-dismiss-notification">
                                        <p class="center-text uppercase">Message field is empty!</p>
                                    </div>
                                </div>   
                                <div class="formFieldWrap">
                                    <label class="field-title contactNameField" for="contactNameField">Name:<span>(required)</span></label>
                                    <input type="text" name="contactNameField" value="" class="contactField requiredField" id="contactNameField">
                                </div>
                                <div class="formFieldWrap">
                                    <label class="field-title contactEmailField" for="contactEmailField">Email: <span>(required)</span></label>
                                    <input type="text" name="contactEmailField" value="" class="contactField requiredField requiredEmailField" id="contactEmailField">
                                </div>
								
								 <div class="formFieldWrap">
                                    <label class="field-title contactEmailField" for="contactEmailField">Password: <span>(required)</span></label>
                                    <input type="password" name="contactPasswordField" value="" class="contactField requiredField requiredEmailField" id="contactEmailField">
                                </div>
								
								 <div class="formFieldWrap">
                                    <label class="field-title contactEmailField" for="contactEmailField">Mobile/Tel: <span>(required)</span></label>
                                    <input type="text" name="contactEmailField" value="" class="contactField requiredField requiredEmailField" id="contactEmailField">
                                </div>
								
								
								
								<div class="formFieldWrap">
                                    <label class="field-title contactEmailField" for="contactEmailField">Address 1: <span>(required)</span></label>
                                    <input type="text" name="contactadd_1" value="" class="contactField requiredField requiredEmailField" id="contactEmailField">
                                </div>
								
								<div class="formFieldWrap">
                                    <label class="field-title contactEmailField" for="contactEmailField">Address 2: <span>(optional)</span></label>
                                    <input type="text" name="contactadd_2" value="" class="contactField" id="contactEmailField">
                                </div>
								
								<div class="formFieldWrap">
                                    <label class="field-title contactEmailField" for="contactEmailField">Address 3: <span>(optional)</span></label>
                                    <input type="text" name="contactadd_3" value="" class="contactField" id="contactEmailField">
                                </div>
								
								<div class="formFieldWrap">
                                    <label class="field-title contactEmailField" for="contactEmailField">Town / City: <span>(required)</span></label>
                                    <input type="text" name="contactadd_4" value="" class="contactField requiredField requiredEmailField" id="contactEmailField">
                                </div>
								
								<div class="formFieldWrap">
                                    <label class="field-title contactEmailField" for="contactEmailField">'.$country_dropdown.' <span>(required)</span></label>
                                    
									<span style="margin-top:-10px;"></span>
                                </div>
								
								
								
                                
                                <div class="formSubmitButtonErrorsWrap">
                                    <input type="submit" class="buttonWrap button button-red contactSubmitButton" id="contactSubmitButton" value="REGISTER" data-formid="contactForm">
                                </div>
                            </fieldset>
                        </form>       
                    </div>
                </div>
                </div>
            </div>
            <div class="decoration"></div>
        </div>
					
					<div id="sell_now"  style="display:none;"><button style="margin-top:10px; width:100%; min-height: 80px; background-color:#056839; font-size:24px;" class="button button-green">Sell Now (£'.$price.')</button></div>
					
					
					<!-- PayPal Logo -->
					<img style="margin-left: auto; margin-right: auto;" src="https://www.paypalobjects.com/webstatic/mktg/Logo/AM_mc_vs_ms_ae_UK.png" border="0" alt="PayPal Acceptance Mark">
					
					</div>';
					
					
					
					
					
	}while($row	= mysql_fetch_assoc($query));
}

##<div><img src="images/contact25_logo_new.png" style="margin-left: auto; margin-right: auto; width:90%; max-width:400px;"  alt="Contact25 Logo"/></div>


?>
 <div style="padding:10px;">&nbsp;</div>
    

<?php echo $result?>



	
