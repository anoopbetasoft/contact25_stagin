<?php 
include ("../../config.php");



?>

<script type="text/javascript">
$(document).ready(function(){

	


	/* if you're logged in as global admin (eBay) - set the delivery price as 99p*/
	function global_admin_postage_reset(){
		/*THIS DOESN'T WORK - TRY TO FIX LATER*/
		if (localStorage.getItem('global_admin') != null) {
				var global_pass = localStorage.getItem('global_admin');
				if (global_pass == 'antony3942834'){
					$('#delivery').html('99');	
				}
			  //...
		}
	}




/* loop through all the gots and save the 'nots' for the query*/
	var test_if_youve_got_this_one = '';
	var got_it = '';
	var needs = 640;
	for (i = 0; i < 640; i++) {
		
		/*check if you've already got it (from local storage) */
		if (localStorage.getItem('got-'+i) != null) {
			var got_it = got_it+'AND (NOT(spares.s_num = '+i+'))';
			needs = needs-1;
		  //...
		}
		/*
		var test_if_youve_got_this_one = localStorage.getItem('got-'+i)
		
		if (test_if_youve_got_this_one == 1){
			var got_it = got_it+'AND (NOT(spares.s_num = '+i+'))';
		} */
       
    }
	
	/*
		save the nots as a session variable
	*/
	$.ajax({
                    url: "/js/ajax/save_nots.php",
                    data: {
							got_it:got_it,
							needs:needs
								
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("we tried to find missing stickers, but you have no internet connection. Please try again when you've got internet access");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
						
                    }

        });
		
		
		$('#swaps').keyup(function() {
			calculate_new_price();

		});
		
		function calculate_new_price(){
			
			var postage_discount = $('#postage_discount').val();
			
			var stickers_supplied_qty = $('#sticker_qty').html();
			/* CALCULATE SWAPS PRICE */
			var swaps_price = 0.1
			if (stickers_supplied_qty>10){
				swaps_price = 0.08;
			}
			if (stickers_supplied_qty>25){
				swaps_price = 0.05;
			}
			if (stickers_supplied_qty>50){
				swaps_price = 0.02;
			}
			if (stickers_supplied_qty>100){
				swaps_price = 0.01;
			}
			
			var suggested_swaps = $('#swaps').val();
			
			if (suggested_swaps>(stickers_supplied_qty*2)){
				alert("Sorry - we only offer a discount fortwice as many swaps as we're supplying stickers. If you'd like to send us more, we'll make sure they get passed on to someone who needs them.");	
				$('#swaps').val(stickers_supplied_qty*2);
				suggested_swaps = stickers_supplied_qty*2;
			}
			var original_price = $('#original_price').val();
			var original_no_delivery = original_price-postage_discount;
			$('#original_sticker_price').html((parseFloat(original_no_delivery).toFixed(2)));
			/* change the price */
			
			var discount 		= (suggested_swaps*swaps_price);
			var discount_price 	= (original_price-discount-postage_discount);
			
			$('#price_inc_swaps').html("£"+(parseFloat(discount_price).toFixed(2)));
			
		}
		
		$('#reserve_stickers').click(function() {
			
			var u_name 		= $('#u_name').val();
			var u_email 	= $('#u_email').val();
			var u_mob 		= $('#u_mob').val();
			var u_address_1 = $('#u_address_1').val();
			var u_address_2 = $('#u_address_2').val();
			var u_address_3 = $('#u_address_3').val();
			var u_address_4 = $('#u_address_4').val();
			var u_postcode 	= $('#u_postcode').val();
			var u_country 	= $('#u_country').val();
			var sticker_list 	= $('#sticker_list').val();
			var original_price 	= $('#original_price').val();
			var sub_total 	= $('#sub_total').val();
			var delivery = $('#delivery').val();
			var swaps = $('#swaps').val();
			var o_voucher = $('#o_voucher').val();
			var sticker_price = $('#sticker_price').val();
			
			
			if (
					(u_name.length == 0)
					||
					(u_email.length == 0)
					||
					(u_address_1.length == 0)
					||
					(u_address_4.length == 0)
					||
					(u_postcode.length == 0)
				){
				alert("Please fill all fields marked with a *");
			}else{
				
				/* Place Order */
				//alert(swaps);
				$.ajax({
                    url: "/js/ajax/place_order2.php",
                    data: {
							u_name:u_name,
							u_email:u_email,
							u_mob:u_mob,
							u_address_1:u_address_1,
							u_address_2:u_address_2,
							u_address_3:u_address_3,
							u_address_4:u_address_4,
							u_postcode:u_postcode,
							u_country:u_country,
							sticker_list:sticker_list,
							original_price:original_price,
							sub_total:sub_total,
							delivery:delivery,
							swaps:swaps,
							o_voucher:o_voucher,
							sticker_price:sticker_price
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("we tried to find missing stickers, but you have no internet connection. Please try again when you've got internet access");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
						$('#reserve_btn').html(data);
                    }

        });
			
				
			}
			
			
			
		});
		
		
		$('#u_email').change(function() {
			var u_email 	= $('#u_email').val();
			var sticker_qty 	= $('#sticker_qty').html();
			
			$.ajax({
                    url: "/js/ajax/customer_voucher_check.php",
                    data: {
							u_email:u_email,
							sticker_qty:sticker_qty
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("Please try again when you've got internet access");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
						if (data>0){
							$('#past_customer_voucher').html("<span style='color:green'><strong>Welcome Back!</strong>  We love it when that happens - to celebrate, we've given you FREE postage - wahoo!</span>");
							$('#postage_discount').val(data);
							$('#o_voucher').val(data); // duplicate reference to voucher / postage discount - 3am job!
							
							
							calculate_new_price();
							
						}
						
                    }
					
			});
		
		});
		
		
		 //$("#price_inc_swaps").html("test");
		
});
</script>
<?php 

include ("../../config.php");

include ("../../classes/class.swaps.php");
$swaps = new swaps();

?><?php 








/*
COMPILE THE JQUERY FOR WHICH ONES THE USER HAS ALREADY GOT ON THE PREVIOUS PAGE THEN SEND THEM THROUGH TO THIS ONE AS (NOT(fdsfs))
*/



/* Get the ID of the person with the most swaps (based on the number the user has ticked they already have */
$done = 0;
$data = '';
$no_swaps = 0;
$sub_total = 0;
$grand_total = 0;
$sub_shipping = 0;

if ($_SESSION['ebay_processing']){
	$sticker_price = 0.99;
}else{
	$sticker_price = 0.5;
}
?>

<?
do{

	$data_array = $swaps->person_with_most_swaps($_SESSION['nots']);

	if ($data_array != 0){
		
		#die($data_array[1]);
		$sticker_price = $swaps->sticker_price($data_array[1]);
		
		
		$total_inc_postage = (($data_array[1]*$sticker_price)+.5);
		$data .= '<tr '.$even.'>
                       
                        <td style="text-align:center;"><span id="sticker_qty">'.$data_array[1].'</span></td>
						<td>&pound;<span id="original_sticker_price">'.number_format($total_inc_postage, 2, '.', '').'</span></td>
                    </tr>';
					$no_swaps = $no_swaps+$data_array[1];
					$sub_total = $sub_total+($data_array[1]*$sticker_price);
					$sub_shipping = $sub_shipping + (($data_array[1]*$sticker_price)+.5);
					$grand_total = $grand_total+$total_inc_postage;
					$sticker_list = $data_array[3];
	}else{

		$done = 1;
	}
	
	
}while($done != 1);



$left_after_purchase = $_SESSION['needs']-$no_swaps;


#echo ($data);
#echo json_encode($data);

?>
<input type="hidden" id="sticker_price" name = "sticker_price" value="<?php echo $sticker_price?>">

<input type="hidden" id="original_price" value="<?php echo $total_inc_postage?>">
<input type="hidden" id="sub_total" value="<?php echo $sub_total?>">
<input type="hidden" id="sticker_list" value="<?php echo $sticker_list?>">
<input type="HIDDEN" id="delivery" value="<?php echo ($total_inc_postage-$sub_total)?>">
<input type="hidden" id="postage_discount" value="0">
<input type="hidden" id="o_voucher" name = "o_voucher" value="0">
<input type="hidden" id="sticker_price" name = "sticker_price" value="<?php echo $sticker_price?>">
<?php if ($_SESSION['needs']>0){?>
<h1></h1>
<h1>We've got <span style="color:green;"><?php echo number_format(($no_swaps/$_SESSION['needs'])*100)?>%</span> of the stickers you need<?php if ($left_after_purchase>0){echo ' - leaving you only <span style="color:green;">'.$left_after_purchase.' left to collect</span>';}?>!</h1>
<p>We've checked our stock and it's good news - we've got <?php if($no_swaps==1){echo ' the 1 sticker';}else{?><span style="color:green; font-weight: bold;"><?php echo $no_swaps?></span> stickers<?php }?> that you need!</p>
<div class="container no-bottom">
               <table cellspacing="0" class="table">
                    <tbody><tr>
                        <th class="table-title">Stickers</th>
                        <th class="table-title">Price (inc delivery)</th>
                        
                    </tr>
                     
                    <?php echo $data?>       
                </tbody></table>
            </div>
<h1>Which stickers will you actually get?</h1>
<p><?php echo $sticker_list?></p>


<?php 

if ($left_after_purchase > 0){
	$text = 'stickers which leaves '.$left_after_purchase.' still to collect.';
?>
<h1>What will I still need?</h1>
<p>You currently need <?php echo $_SESSION['needs']?> to finish your collection.  After this order you will receive <?php echo $no_swaps?> <?php echo $text?></p>
<?		
}
?>

<h1>Your Details</h1>
<p>Fill in your details and reserve your stickers (so that nobody else buys them!)</p>
<input type="text" id="u_name" name="u_name" placeholder="Your Name*" style="margin-top:5px;">
<input type="text" id="u_email" name="u_email" placeholder="Email*" style="margin-top:5px;"><span id="past_customer_voucher"></span>
<input type="text" id="u_mob" name="u_mob" placeholder="Mobile" style="margin-top:5px;">
<input type="text" id="u_address_1" name="u_address_1" placeholder="Address 1*" style="margin-top:5px;">
<input type="text" id="u_address_2" name="u_address_2" placeholder="Address 2" style="margin-top:5px;">
<input type="text" id="u_address_3" name="u_address_3" placeholder="Address 3" style="margin-top:5px;">
<input type="text" id="u_address_4" name="u_address_4" placeholder="Town/City*" style="margin-top:5px;">
<input type="text" id="u_postcode" name="u_postcode" placeholder="Postcode/Zip*" style="margin-top:5px;">

<select  id="u_country" name="u_country"  style="margin-top:5px; padding:5px; color:#999">
	<option value=15>UK</option>
    <?php 
	$sql = 'SELECT * FROM countries WHERE (NOT(c_id = 15)) ORDER BY c_name ASC ';
	$query = mysql_query($sql);
	$row = mysql_fetch_assoc($query);
	$num_rows = mysql_num_rows($query);
	if ($num_rows>0){
		do{
			echo '<option value="'.$row['c_id'].'">'.$row['c_name'].' (min order 10 stickers)</option>';
		}while($row = mysql_fetch_assoc($query));
	}
	?>
</select>
<?php if (1>2){?>
<input type="hidden" id="u_country" name="u_country" value="15">
<?php }?>
<h1>&nbsp;</h1>
<h1>Got any Swaps?</h1>
<p>We'll pay up to 10p per sticker you send us so long as it reaches us in '100% new' condition  (please don't send curled or crumpled stickers - we won't accept them). </p>

<h1>Price including Swaps</h1>

<div class="container no-bottom">
               <table cellspacing="0" class="table">
                    <tbody><tr>
                      <th class="table-title">Swaps</th>
                        <th class="table-title">New Price</th>
                        
                    </tr>
                    <tr>
                       
                      <td style="width:50%;"><input type="number" id="swaps" name="swaps" placeholder="Number of swaps (if any)?" style="width:170px"  ></td>
						<td><span id="price_inc_swaps"><?php echo '&pound;'.number_format($total_inc_postage, 2, '.', '')?></span></td>
                    </tr>
                     
                          
                </tbody></table>
</div>
<div id="reserve_btn">
<a class="button-big-icon button-green" id="reserve_stickers" style=" cursor:pointer;margin-top:5px;">Complete (then pay with PayPal)</a>
</div>
<h1>&nbsp;</h1>
<?php }else{?>
<h1>&nbsp;</h1>
<h1>Please untick some of the boxes above and we'll check if we've got them available</h1>
<?php }?>