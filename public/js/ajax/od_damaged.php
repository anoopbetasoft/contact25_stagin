<?php 
 
include_once("../../include/config.php");

/* duplicate order*/
$sql = "
	SELECT 
		*
	FROM 
		orders
	WHERE 
		orders.o_id ='".$_POST['order_id']."' 
	";

$query = mysqli_query($db,$sql);
$row = mysqli_fetch_assoc($query);
$num_rows = mysqli_num_rows($query);
if ($num_rows>0){
	do{
		
		$sql = '
					INSERT INTO
						orders
							(
								o_u_id,
								o_fullilled_by,
								o_name,
								o_email,
								o_ebay_userID,
								
								o_address_del_1,
								o_address_del_2,
								o_address_del_3,
								o_address_del_4,
								o_postcode_del,
								
								o_country,
								o_shipping_service,
								o_currency,
								o_sub_total,
								o_delivery,
								
								o_swaps_qty,
								o_swaps_price,
								o_total,
								o_dispatched,
								o_is_dispatched,
								
								o_amazon_dispatched,
								o_print_order,
								o_label_printed,
								o_awaiting_swaps,
								o_voucher,
								
								o_paid,
								o_email_followup,
								o_amazon_order_id,
								o_ebay_order_id,
								o_paypal_payment_id,
								
								o_paypal_token,
								o_paypal_payer_id,
								o_delivery_note,
								o_dealwithlater,
								o_locked_by_user,
								
								o_locked_until
								
							)
						VALUES
							(
								"'.$row['o_u_id'].'",
								"'.$row['o_fullilled_by'].'",
								"'.$row['o_name'].'",
								"'.$row['o_email'].'",
								"'.$row['o_ebay_userID'].'",
								
								"'.$row['o_address_del_1'].'",
								"'.$row['o_address_del_2'].'",
								"'.$row['o_address_del_3'].'",
								"'.$row['o_address_del_4'].'",
								"'.$row['o_postcode_del'].'",
								
								"'.$row['o_country'].'",
								"'.$row['o_shipping_service'].'",
								"'.$row['o_currency'].'",
								"'.$row['o_sub_total'].'",
								"'.$row['o_delivery'].'",
								
								"'.$row['o_swaps_qty'].'",
								"'.$row['o_swaps_price'].'",
								"'.$row['o_total'].'",
								"'.$row['o_dispatched'].'",
								"'.$row['o_is_dispatched'].'",
								
								"'.$row['o_amazon_dispatched'].'",
								"'.$row['o_print_order'].'",
								"'.$row['o_label_printed'].'",
								"'.$row['o_awaiting_swaps'].'",
								"'.$row['o_voucher'].'",
								
								"'.$row['o_paid'].'",
								"'.$row['o_email_followup'].'",
								"'.$row['o_amazon_order_id'].'",
								"'.$row['o_ebay_order_id'].'",
								"'.$row['o_paypal_payment_id'].'",
								
								"'.$row['o_paypal_token'].'",
								"'.$row['o_paypal_payer_id'].'",
								"'.$row['o_delivery_note'].'",
								"'.$row['o_dealwithlater'].'",
								"'.$row['o_locked_by_user'].'",
								
								"'.$row['o_locked_until'].'"
								
								
							)
		';
		
		mysqli_query($db,$sql);
		
		$user = $row['o_u_id'];
		
		
	}while($row = mysqli_fetch_assoc($query));
}

/* duplicate order*/
$sql = "
	SELECT 
		*
	FROM 
		order_details
	WHERE 
		order_details.od_o_id = '".$_POST['order_id']."' 
	AND
		order_details.od_s_id = '".$_POST['s_id']."'
	";

$query = mysqli_query($db,$sql);
$row = mysqli_fetch_assoc($query);
$num_rows = mysqli_num_rows($query);
if ($num_rows>0){
	do{
		
		
		
		$sql = '
					INSERT INTO
						order_details
							(
								od_o_id,
								od_num,
								od_s_id,
								od_qty,
								od_quality,
								
								od_album,
								od_price,
								od_purchased_for,
								od_ebay_item_ID,
								od_ebay_transaction_ID,
								
								od_ebay_shipped,
								od_amazon_shipped,
								od_ebay_relisted,
								od_amazon_price_checked,
								od_purchased,
								
								od_rm_label,
								od_purchased_via,
								od_mark_dispatched_date,
								od_suggestion,
								od_amazon_itemcode,
								
								od_po,
								od_returned,
								od_sent_direct,
								od_cancelled
								
							)
						VALUES
							(
								"'.calc_new_order_id_from_user($user).'",
								"'.$row['od_num'].'",
								"'.$row['od_s_id'].'",
								"'.$row['od_qty'].'",
								"'.$row['od_quality'].'",
								
								"'.$row['od_album'].'",
								"'.$row['od_price'].'",
								"'.$row['od_purchased_for'].'",
								"'.$row['od_ebay_item_ID'].'",
								"'.$row['od_ebay_transaction_ID'].'",
								
								"'.$row['od_ebay_shipped'].'",
								"'.$row['od_amazon_shipped'].'",
								"'.$row['od_ebay_relisted'].'",
								"'.$row['od_amazon_price_checked'].'",
								"0",
								
								"0",
								"'.$row['od_purchased_via'].'",
								"'.$row['od_mark_dispatched_date'].'",
								"'.$row['od_suggestion'].'",
								"'.$row['od_amazon_itemcode'].'",
								
								"'.$row['od_po'].'",
								"'.$row['od_returned'].'",
								"'.$row['od_sent_direct'].'",
								"'.$row['od_cancelled'].'"
								
							)
		';
		
		mysqli_query($db,$sql);
	
		
	}while($row = mysqli_fetch_assoc($query));
}




/* request refund */
$sql = "
	SELECT 
		order_details.od_o_id,
		order_details.od_qty,
		order_details.od_po,
		stock_c25.s_price_buy,
		stock_c25.s_SKU,
		stock_c25.s_label,
		users.u_name,
		users.u_email
	FROM 
		order_details,
		stock_c25,
		users
	WHERE 
		order_details.od_o_id='".$_POST['order_id']."' 
	AND 
		od_s_id = '".$_POST['s_id']."'
	AND 
		stock_c25.s_s_id = order_details.od_s_id
	AND 
		order_details.od_purchased_via = users.u_id
	";

$query = mysqli_query($db,$sql);
$row = mysqli_fetch_assoc($query);
$num_rows = mysqli_num_rows($query);
if ($num_rows>0){
	do{
		
		include("/var/www/vhosts/contact25.com/httpdocs/classes/class.mail.php"); 
		$send_cron = new email();

		$to_email 			= "antony@contact25.com";
		$to_name			= "Antony Vila";
		$from_email       	= "info@contact25.com";
		$from_email   		= "contact25.com";
		$from_name			= "Contact25";
		$title    			= "Product Arrived Damaged (PO ".$_POST['order_id'].")";
		$message    		= "Hi ".$row['u_name']."
		
The following product unfortunately arrived damaged:
<span style='font-weight:bold;font-size:16px;color:green;'>(SKU: ".$row['s_SKU'].")
".$row['s_label']." x ".$row['od_qty']."</span>

The purchase order was <span style='font-weight:bold;font-size:20px;color:green;'>".$row['od_po']."</span>

We have ordered this again which will go through on our next order

Please could you process a refund for the damaged goods.

Thanks
"; // optional, comment out and test
		$email_sent			= $send_cron->cron_mail($to_email, $from_email, $to_name, $from_name, $message, $title);
		
	}while($row = mysqli_fetch_assoc($query));
}




function calc_new_order_id_from_user($u_id){
	
	
	$sql = "SELECT o_id FROM orders WHERE o_u_id = '".$u_id."' ORDER BY o_id DESC LIMIT 1";
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	if ($num_rows>0){
		do{
			
			return $row['o_id'];
			
		}while($row = mysqli_fetch_assoc($query));
	}

	
}
		






echo "<div style='color:red; padding:20px;'><i data-icon=')' class='linea-icon linea-basic fa-fw'></i>Reported as damaged <br>
Re-ordered for next delivery</div>";


?>