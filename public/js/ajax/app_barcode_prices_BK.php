<?php 

$hostname_tigers = "88.208.249.28";
$database_tigers = "contact25";
$username_tigers = "contact25-un";
$password_tigers = "mrW09n~8"; 
$tigers = mysql_pconnect($hostname_tigers, $username_tigers, $password_tigers) or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_select_db($database_tigers, $tigers) or die("Please refresh browser");
session_start();


$barcode = $_POST['barcode'];
	$barcode = '5051561040740';
	$sql = 'SELECT s_id, s_ISBN10, s_buy_price_checked FROM spares WHERE spares.s_ISBN13 = "'.$barcode.'" LIMIT 1';
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	if ($num_rows>0){
		do{
			/* only check if the price hasn't been checked in the last 30 days*/
			if( strtotime($row['s_buy_price_checked']) < strtotime('-30 day') ) {
			
				include_once('/home/vhosts/contact25.com/httpdocs/classes/class.amazon.php');
				$amazon 				= new amazon();

				$amazon->amazon_aws_definitions_uk();


				$asin_and_s_id = array(array($row['s_id'], $row['s_ISBN10']));
				$serviceUrl 			= "mws-eu.amazonservices.com";


				$response = $amazon->amazon_aws_update_buy_price($row['s_ISBN10'], $serviceUrl, $row['s_id'], '');
			}
			
		}while($row= mysqli_fetch_assoc($query));
	}




	$barcode = $_POST['barcode'];
	$barcode = '5051561040740';
	$sql = 'SELECT s_price, s_price_good, s_price_ok,s_ISBN10, s_id FROM spares WHERE spares.s_ISBN13 = "'.$barcode.'" LIMIT 1';
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	$output = array();

	if ($num_rows>0){
		do{
			

			if ($row['s_price']>0){
				$price_new = $row['s_price'];
			}else{
				$price_new = 19.99;
			}
			
			if ($row['s_price_good']>0){
				$price_good = $row['s_price_good'];
			}else{
				$price_good = floor($price_new * .85);
				$price_good = $price_good + .99;
			}
			
			if ($row['s_price_ok']>0){
				$price_ok = $row['s_price_ok'];
			}else{
				$price_ok = floor($price_new * .75);
				$price_ok = $price_ok + .99;
			}
			
			if ($price_good<$price_ok){
				$price_ok = floor($price_good * .85);
				$price_ok = $price_ok + .99;
			}
			
			echo json_encode(
						array(
							earnings_after_fees($price_new, est_post($row['s_id'], 1), est_fee($price_new)),
							earnings_after_fees($price_good, est_post($row['s_id'], 1), est_fee($price_good)),
							earnings_after_fees($price_ok, est_post($row['s_id'], 1), est_fee($price_ok)),
							est_post($row['s_id'], 1),
							est_post($row['s_id'], 2),
							est_post($row['s_id'], 3),
							est_fee($price_new),
							est_fee($price_good),
							est_fee($price_ok),
							$price_new, 
							$price_good, 
							$price_ok
							 )
					); 
			die();
			
			
		}while($row = mysqli_fetch_assoc($query));
	}else{
		$output = array("-", "-", "-");

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
	
	return number_format(($price*.5), 2, '.', '');
}

function earnings_after_fees($price, $postage, $fee){
	return number_format(($price-$postage-$fee), 2, '.', '');
}

?>