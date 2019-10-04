<script type="text/javascript">

		
		
		$('#pay_now').click(function() {
			$('form#paypal_form').submit();
		});
		
		
		
	
		

</script>

<?php 

include ("../../config.php");

include_once ("../../classes/class.swaps.php");

$swaps_price = 0;
if ($_POST['swaps']>0){
$swaps = new swaps();
$swaps_price = $swaps->swaps_price($_POST['swaps']);
}



/* check if existing customer */

$sql = 'SELECT 	
			*
		FROM
			users
		WHERE
			u_email = "'.$_POST['u_email'].'"';
$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);

if ($num_rows>0){
	do{
		$c_id = $row['u_id'];
		/*	Update user */
		$sql = '
			UPDATE 
				users
			SET		
				u_name = "'.ucwords($_POST['u_name']).'",
				u_email = "'.$_POST['u_email'].'",
				u_mob = "'.$_POST['u_mob'].'",
				u_address_1 = "'.ucwords($_POST['u_address_1']).'",
				u_address_2 = "'.ucwords($_POST['u_address_2']).'",
				u_address_3 = "'.ucwords($_POST['u_address_3']).'",
				u_address_4 = "'.ucwords($_POST['u_address_4']).'",
				u_postcode = "'.strtoupper ($_POST['u_postcode']).'",
				u_country = "'.$_POST['u_country'].'"
			WHERE
				u_id = 	"'.$row['u_id'].'"
					
		';
		mysql_query($sql);
			
	}while($row = mysql_fetch_assoc($query));
	
	
}else{
	/*	Add new user */
$sql = '
			INSERT INTO 
				users
					(
						u_name,
						u_email,
						u_mob,
						u_address_1,
						u_address_2,
						u_address_3,
						u_address_4,
						u_postcode,
						u_country
					)
				VALUES
					(
						"'.ucwords($_POST['u_name']).'",
						"'.$_POST['u_email'].'",
						"'.$_POST['u_mob'].'",
						"'.ucwords($_POST['u_address_1']).'",
						"'.ucwords($_POST['u_address_2']).'",
						"'.ucwords($_POST['u_address_3']).'",
						"'.ucwords($_POST['u_address_4']).'",
						"'.strtoupper ($_POST['u_postcode']).'",
						"'.$_POST['u_country'].'"
						
					)
					
';
mysql_query($sql);

/*select the customer you've just inserted */
	$sql = 'SELECT 	
				*
			FROM
				users
			WHERE
				u_email = "'.$_POST['u_email'].'"';
	$query = mysql_query($sql);
	$row = mysql_fetch_assoc($query);
	$num_rows = mysql_num_rows($query);
	do{
		/* save the ID */
		$c_id = $row['u_id'];	
	}while($row = mysql_fetch_assoc($query));


}


/*add new order*/
/*	Add new user */
if ($_POST['swaps']>0){
	$awaiting_swaps = 1;
}else{
	$awaiting_swaps = 0;
}

if ($_SESSION['ebay_processing']){
	$paid = 1;
	$sticker_order_list = explode(" ", $_POST['sticker_list']);
	$count = count($sticker_order_list);
	$delivery = $_POST['delivery']*$count;
}else{
	$paid = 0;
	$delivery = $_POST['delivery'];
}


$sql = '
			INSERT INTO 
				orders
					(
						o_u_id,
						o_name,
						o_address_del_1,
						o_address_del_2,
						o_address_del_3,
						o_address_del_4,
						o_postcode_del,
						o_country,
						
						o_sub_total,
						o_delivery,
						o_swaps_qty,
						o_swaps_price,
						o_total,
						o_dispatched,
						o_awaiting_swaps,
						o_voucher,
						o_paid
					)
				VALUES
					(
						"'.$c_id.'",
						"'.ucwords($_POST['u_name']).'",
						"'.ucwords($_POST['u_address_1']).'",
						"'.ucwords($_POST['u_address_2']).'",
						"'.ucwords($_POST['u_address_3']).'",
						"'.ucwords($_POST['u_address_4']).'",
						"'.strtoupper ($_POST['u_postcode']).'",
						"'.$_POST['u_country'].'",
						
						"'.$_POST['sub_total'].'",
						"'.$delivery.'",
						"'.$_POST['swaps'].'",
						"'.$_POST['swaps']*$swaps_price.'",
						"'.($_POST['sub_total']+$delivery-$_POST['o_voucher']-($_POST['swaps']*$swaps_price)).'",
						"'.date("Y-m-d").'",
						"'.$awaiting_swaps.'",
						"'.$_POST['o_voucher'].'",
						"'.$paid.'"
						
					)
					
';
mysql_query($sql);

$sql = 'SELECT 	
				o_id
			FROM
				orders
			ORDER BY
				o_id
			DESC
			
			LIMIT 1
			';
	$query = mysql_query($sql);
	$row = mysql_fetch_assoc($query);
	$num_rows = mysql_num_rows($query);
	do{
		/* save the ID */
		$o_id = $row['o_id'];	
	}while($row = mysql_fetch_assoc($query));



$sticker_order_list = explode(" ", $_POST['sticker_list']);

$i = 0;
foreach ($sticker_order_list as &$value) {
	
	
	$order_num = str_replace(",","",$sticker_order_list[$i]);
	
	
	$sql = 'INSERT INTO
				order_details
			(
				od_o_id,
				od_num,
				od_album,
				od_price			
			)
			VALUES
			(
				"'.$o_id.'",
				"'.$order_num.'",
				"1",
				"'.$_POST['sticker_price'].'"
			
			)
				
	
	';
    mysql_query($sql);
	$i ++;
	
}



#

/*
sticker_list:sticker_list,
							original_price:original_price
							echo $_POST['u_name'];*/
							
?>
<h1>Thank you - Your Order Number is <span style="color:green"><?php echo $o_id?></span></h1>

<?


if ($_POST['swaps']>0){

	?>
    <h1>Print the Swaps Form</h1>
    <p>Print the form below and enclose your swaps (we will only send your stickers once we've received both your swaps and payment)</p>
	<a class="button-big-icon button-green" id="complete_collection_check" style=" cursor:pointer;" href="http://contact25.com/tcpdf/examples/swaps_form.php?id=<?php echo $o_id?>" target="_blank">Swaps Form (pdf)</a>
	
	<?
}
?>

<h1>&nbsp;</h1>
<h1>Pay with PayPal</h1>

 <form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="paypal_form" id="paypal_form" target="_blank">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="antony.vila@jukeboxmarketing.com">
<input type="hidden" name="lc" value="GB">
<input type="hidden" name="item_name" value="contact25.com REF <?php echo $o_id?>">
<input type="hidden" name="item_number" value="<?php echo $_POST['sticker_list']?>">
<input type="hidden" name="amount" value="<?php echo ($_POST['sub_total']-($_POST['swaps']*$swaps_price))?>">
<input type="hidden" name="currency_code" value="GBP">
<input type="hidden" name="button_subtype" value="services">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="shipping" value="<?php echo $_POST['delivery']-$_POST['o_voucher']?>">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">

</form>
<a class="button-big-icon button-red" id="pay_now" style=" cursor:pointer;">Buy Now via Paypal</a>
