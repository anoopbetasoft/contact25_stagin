<?php 
$hostname_tigers = "88.208.249.28";
$database_tigers = "contact25";
$username_tigers = "contact25-un";
$password_tigers = "mrW09n~8";
$tigers = mysql_pconnect($hostname_tigers, $username_tigers, $password_tigers) or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_select_db($database_tigers, $tigers) or die("Please refresh browser");

session_start();
/* STAGE 2: CREATE ORDER*/

/* SELECT ORDER INFORMATION */
$sql = 'select 
			spares.s_label,
			spares.s_id,
			spares.s_url,
			spares.s_price,
			spares.s_price_like_new,
			spares.s_price_good,
			spares.s_price_ok,
			cart.*
		from 
			spares,
			cart 
		WHERE 
			cart.ct_session = "'.session_id().'"
		AND
			cart.ct_s_id = spares.s_id
		ORDER BY cart.ct_id ASC';
#die($sql);
$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);
$sql_order_details = '';

if ($num_rows>0){
	
	$sql_order_details_array = array();
	
	do{
		
		
				$quality_display = display_quality($row['ct_quality']);
				$price = price_based_on_quality($row['ct_quality'], $row['s_price'],$row['s_price_like_new'],$row['s_price_good'],$row['s_price_ok']);
		
		
		$sql_order_details = 'INSERT INTO order_details
								(
									od_o_id,
									od_num,
									od_s_id,
									od_qty,
									od_quality,
									od_album,
									od_price,
									od_ebay_item_ID
									
								)
							VALUES
								(
									"-12345",
									"['.$quality_display.']'.mb_strimwidth($row['s_label'], 0, 140, "...").'",
									"'.$row['ct_s_id'].'",
									"'.$row['ct_qty'].'",
									"'.$row['ct_quality'].'",
									"'.$row['ct_album'].'",
									"'.$price.'",
									"1"
								)
					';
				array_push($sql_order_details_array, $sql_order_details);
				$sub_total = $sub_total + ($price*$row['ct_qty']);
				$_SESSION['sub_total'] = $sub_total;
			}while($row = mysql_fetch_assoc($query));
}

if (strlen($_POST['a_address_1'])>0){
	
	/* SEPARATE DELIVERY ADDRESS POSTED*/	
	/* CREATE ORDER */
	if (strlen($_SESSION['voucher_name'])>0){
		$voucher_value = voucher_value($sub_total);
		$sub_total = $sub_total-$voucher_value;
	}
	$_SESSION['sub_total'] = $sub_total;
	
	$sql = "
		INSERT INTO 
			orders
				(
					o_u_id,
					o_name,
					o_email,
					o_address_del_1,
					o_address_del_2,
					o_address_del_3,
					o_address_del_4,
					o_postcode_del,
					o_country,
					o_shipping_service,
					o_currency,
					o_voucher,
					o_sub_total,
					o_delivery,
					o_total,
					o_dispatched,
					o_paypal_payment_id,
					o_paypal_token,
					o_paypal_payer_id
					
				) 
			VALUES
				(
					'".$_SESSION['u_id']."',
					'".$_POST['a_name']."',
					'".$_POST['u_email']."',
					'".$_POST['a_address_1']."',
					'".$_POST['a_address_2']."',
					'".$_POST['a_address_3']."',
					'".$_POST['a_address_4']."',
					'".$_POST['a_postcode']."',
					'".$_POST['a_country']."',
					'Standard',
					'GBP',
					'".$voucher_value."',
					'".($sub_total+$voucher_value)."',
					'".$delivery."',
					'".$sub_total."',
					'".date("Y-m-d H:i:s")."',
					'".$_POST['paymentId']."',
					'".$_POST['token']."',
					'".$_POST['PayerID']."'
					
				)
			
	";
	mysql_query($sql);
	
	$_SESSION['name'] 			= $_POST['a_name'];
	$_SESSION['address_1'] 		= $_POST['a_address_1'];
	$_SESSION['address_2'] 		= $_POST['a_address_2'];
	$_SESSION['address_3'] 		= $_POST['a_address_3'];
	$_SESSION['address_4'] 		= $_POST['a_address_4'];
	$_SESSION['postcode'] 		= $_POST['a_postcode'];
	$_SESSION['country_code'] 	= country_code($_POST['a_country']);
	$_SESSION['mob'] 			= $_POST['u_mob'];
	
	
	

}else{
		/* BILLING ADDRESS & DELIVERY THE SAME*/	
		/* CREATE ORDER */
		if (strlen($_SESSION['voucher_name'])>0){
			$voucher_value = voucher_value($sub_total);
			$sub_total = $sub_total-$voucher_value;
		}

		$sql = "
			INSERT INTO 
				orders
					(
						o_u_id,
						o_name,
						o_email,
						o_address_del_1,
						o_address_del_2,
						o_address_del_3,
						o_address_del_4,
						o_postcode_del,
						o_country,
						o_shipping_service,
						o_currency,
						o_voucher,
						o_sub_total,
						o_delivery,
						o_total,
						o_dispatched,
						o_paypal_payment_id,
						o_paypal_token,
						o_paypal_payer_id
					) 
				VALUES
					(
						'".$_SESSION['u_id']."',
						'".$_POST['u_name']."',
						'".$_POST['u_email']."',
						'".$_POST['u_address_1']."',
						'".$_POST['u_address_2']."',
						'".$_POST['u_address_3']."',
						'".$_POST['u_address_4']."',
						'".$_POST['u_postcode']."',
						'".$_POST['u_country']."',
						'Standard',
						'GBP',
						'".$voucher_value."',
						'".($sub_total+$voucher_value)."',
						'".$delivery."',
						'".$sub_total."',
						'".date("Y-m-d H:i:s")."',
						'".$_POST['paymentId']."',
						'".$_POST['token']."',
						'".$_POST['PayerID']."'
						
						
					)
				
		";
		mysql_query($sql);
		
		$_SESSION['name'] 			= $_POST['u_name'];
		$_SESSION['address_1'] 		= $_POST['u_address_1'];
		$_SESSION['address_2'] 		= $_POST['u_address_2'];
		$_SESSION['address_3'] 		= $_POST['u_address_3'];
		$_SESSION['address_4'] 		= $_POST['u_address_4'];
		$_SESSION['postcode'] 		= $_POST['u_postcode'];
		$_SESSION['country_code'] 	= country_code($_POST['u_country']);
		$_SESSION['mob'] 			= $_POST['u_mob'];
		$_SESSION['email'] 			= $_POST['u_email'];
		
		
		
}


/*select the order ID*/
$o_id = last_order();
$_SESSION['o_id'] = $o_id;
foreach ($sql_order_details_array as $sql) {
	/*run each line of sql after replacing in the new orderID*/
	$run_sql = str_replace('-12345',$o_id,$sql);
  	mysql_query($run_sql);
}

echo $o_id;
	function display_quality($quality){
		if ($quality == 1){
			return "New";	
		}
		if ($quality == 2){
			return "Like New";	
		}
		if ($quality == 3){
			return "Good";	
		}
		if ($quality == 4){
			return "OK";	
		}
	}
function price_based_on_quality ($quality, $price_1, $price_2, $price_3, $price_4){
		if ($quality == 1){
			return $price_1;	// new
		}
		if ($quality == 2){
			return $price_2;	// like new
		}
		if ($quality == 3){
			return $price_3;	// good
		}
		if ($quality == 4){
			return $price_4;	// ok
		}
	}
function voucher_value($sub_total){
		
		session_start();
		
		if ($_SESSION['min_spend']>0){
			
			if ($_SESSION['min_spend']<$sub_total){
				
					/* voucher type 1 - %age discount*/
					if ($_SESSION['voucher_percentage'] > 0){
						return ($sub_total*($_SESSION['voucher_percentage']/100));
					}
					
					/* voucher type 2 - money off*/
					if ($_SESSION['money_off'] > 0){
						return $_SESSION['money_off'];
					}
			
			}

		}else{
			
					/* voucher type 1 - %age discount*/
					if ($_SESSION['voucher_percentage'] > 0){
						return ($sub_total*($_SESSION['voucher_percentage']/100));
					}
					
					/* voucher type 2 - money off*/
					if ($_SESSION['money_off'] > 0){
						return $_SESSION['money_off'];
					}
			
		}
		
		
	
		
	}
function country_code($c_id){
		$sql = 'select 
					c_code		 		
				from 
					countries
				WHERE
					c_id = "'.$c_id.'"';
		
		$query = mysql_query($sql);
		$row = mysql_fetch_assoc($query);
		$num_rows = mysql_num_rows($query);
		
		if ($num_rows>0){
			do{	
				return $row['c_code'];
			}while($row = mysql_fetch_assoc($query));
		}
	}
function last_order(){
		
		$sql = 'select 
					o_id		 		
				from 
					orders
				ORDER BY
					o_id
				DESC
				LIMIT 	1';
		
		$query = mysql_query($sql);
		$row = mysql_fetch_assoc($query);
		$num_rows = mysql_num_rows($query);
		
		if ($num_rows>0){
			do{	
				return $row['o_id'];
			}while($row = mysql_fetch_assoc($query));
		}
	}
/*
<input type="hidden" name="item_name_<?php echo $i?>" value="ORDER: <?php echo $o_id?>">
 <input type="hidden" name="item_number_<?php echo $i?>" value="ORDER: <?php echo $o_id?>">
 <input type="hidden" name="amount_<?php echo $i?>" value="<?php echo number_format($sub_total, 2, '.', '')?>">
 <input type="hidden" name="quantity_<?php echo $i?>" value="1">*/
 
 ?>



	
	
	