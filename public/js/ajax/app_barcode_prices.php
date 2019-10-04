<?php 

$hostname_tigers = "88.208.249.28";
$database_tigers = "contact25";
$username_tigers = "contact25-un";
$password_tigers = "mrW09n~8";
$tigers = mysql_pconnect($hostname_tigers, $username_tigers, $password_tigers) or trigger_error(mysql_error(),E_USER_ERROR); 

if(strpos( $_SERVER['HTTP_HOST'], 'http://app.contact25.com') !== false){
    $root_loaction = '/';
}else{
	$root_loaction = '/home/vhosts/contact25.com/httpdocs/';
}



mysql_select_db($database_tigers, $tigers) or die("Please refresh browser");
session_start();



	$barcode = $_POST['barcode'];
	//$barcode = '5010305051008';
	$sql = 'SELECT s_id, s_ISBN10, s_buy_price_checked FROM spares WHERE spares.s_ISBN13 = "'.$barcode.'" AND s_ISBN10 != -1 LIMIT 1';
	
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	#$sql_test = 'UPDATE test SET value = "1#'.addslashes($sql).'"';
	#mysql_query($sql_test);
	if ($num_rows>0){
		do{
			
			$s_id = $row['s_id'];
			
			
			/* only check if the price hasn't been checked in the last 30 days*/
			if( strtotime($row['s_buy_price_checked']) < strtotime('-30 day') ) {
				
					
				include_once($root_loaction.'classes/class.amazon.php');
				$amazon 				= new amazon();

				$amazon->amazon_aws_definitions_uk();

				$asin_and_s_id = array(array($row['s_id'], $row['s_ISBN10']));
				$serviceUrl 			= "mws-eu.amazonservices.com";

				$response = $amazon->amazon_aws_update_buy_price($row['s_ISBN10'], $serviceUrl, $row['s_id'], '');

			}
			
		}while($row= mysqli_fetch_assoc($query));
	}else{
		
		/* new product - setup*/
		#$sql_test = 'UPDATE test SET value = "3#'.addslashes($sql).'"';
		#mysql_query($sql_test);
		include_once($root_loaction.'classes/class.amazon.php');
		$amazon 				= new amazon();
		$amazon->amazon_aws_definitions_uk();
		$response = $amazon->amazon_aws_barcode_search($barcode, $serviceUrl);
		
		$sql = 'SELECT s_id, s_ISBN10, s_buy_price_checked FROM spares WHERE spares.s_ISBN13 = "'.$barcode.'"  AND s_ISBN10 != -1 LIMIT 1';
		
		$query = mysqli_query($db,$sql);
		$row = mysqli_fetch_assoc($query);
		$num_rows = mysqli_num_rows($query);

		if ($num_rows>0){
			do{
				
				$s_id = $row['s_id'];
				$asin_and_s_id = array(array($row['s_id'], $row['s_ISBN10']));
				$serviceUrl 			= "mws-eu.amazonservices.com";
				$response = $amazon->amazon_aws_update_buy_price($row['s_ISBN10'], $serviceUrl, $row['s_id'], '');
				
			}while($row= mysqli_fetch_assoc($query));
		}else{
			## no results on Amazon
			echo json_encode(
						array(
							("-")
							 )
					); 
			die();
			
		}
		
	}

	
	## no results on Amazon
	/*		echo json_encode(
						array(
							(1+$s_id)
							 )
					); 
			die();*/

	
	$sql = 'SELECT * FROM prices WHERE prices.p_s_id = "'.$s_id.'" ORDER BY (prices.p_amount+p_shipping)';
	#$sql_test = 'UPDATE test SET value = "2#'.addslashes($sql).'"';
	#mysql_query($sql_test);
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	$output = array();

	if ($num_rows>0){
		$current_new_price = '';
		$current_good_price = '';
		$current_ok_price = '';
		
		do{
			

			if (($row['p_sub_condition']=='New')&&(!$current_new_price>0)){
				$current_new_price = $row['p_amount'];
			}
			
			if ((($row['p_sub_condition']=='Good')||($row['p_sub_condition']=='VeryGood')||($row['p_sub_condition']=='Mint'))&&(!$current_good_price>0)){
				$current_good_price = $row['p_amount'];
			}
			
			if ((($row['p_sub_condition']=='Acceptable'))&&(!$current_good_price>0)){
				$current_ok_price = $row['p_amount'];
			}

		}while($row = mysqli_fetch_assoc($query));
		
		$estimated_post = est_post($s_id);
		
		/*check for existence of prices*/
		if (!$current_new_price>0){
			$current_new_price = 19.99;
		}
		if (!$current_good_price>0){
			$current_good_price = $current_new_price-1;
		}
		if (!$current_ok_price>0){
			$current_ok_price = $current_good_price-1;
		}
		
		
		echo json_encode(
						array(
							est_fee($current_new_price),
							est_fee($current_good_price),
							est_fee($current_ok_price),
							$estimated_post,
							$estimated_post,
							$estimated_post
							 )
					); 
			die();
		
	}else{
		/* amazon doesn't have any available - set an initial valuation default*/
		$output = array("20", "17.50", "15",3.5,3.5,3.5);

	}

echo json_encode($output);

function est_post($s_id, $condition){
	
	$sql = 'SELECT p_shipping FROM prices WHERE prices.p_s_id = "'.$s_id.'" AND p_shipping>0 LIMIT 1';
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	$output = array();

	if ($num_rows>0){
		do{
			
			return number_format(($row['p_shipping']), 2, '.', '');
			
		}while($row = mysqli_fetch_assoc($query));
	}else{
		return number_format((2.80), 2, '.', '');
	}
}

function est_fee($price){
	
	$fee_calc = ($price*.45);
	if ($fee_calc<0.20){
		$fee_calc = 0.2;
	}
		
	return	number_format(($fee_calc), 2, '.', '');
	
	
	
}

function earnings_after_fees($price, $postage, $fee){
	return number_format(($price-$postage-$fee), 2, '.', '');
}

?>