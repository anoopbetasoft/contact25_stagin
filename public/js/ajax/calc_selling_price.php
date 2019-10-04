<?php 







function calc_price($postage, $vat){
	
	/* Variables */
	$end_fee_ebay 		= 0.085;
	$paypal_fixed 		= 0.2;
	$paypal_variable 	= 0.034;
	$minimum_profit		= 1;
	
	/* Initial Test Price*/
	$cost_price	= $_POST['var_buy_price'];
	$test_price = $cost_price+1;
	$end_price	= $cost_price+10000000000;
	
	do{
		
		/* work out if it's profitable at this price - if not, increase by 1p until you get a profitable price*/
		
		/* Calc Ebay Final value fee*/
		$ebay_final = $test_price*$end_fee_ebay;
		/* Calc PayPal fixed fee*/
		$paypal_fixed = $paypal_fixed;
		/* Calc PayPal variable fee*/
		$paypal_var = $test_price*$paypal_variable;
		/* VAT */
		$vat_value = $test_price*$vat;
		
		
		/* TOTAL COSTS*/
		$total_costs = $ebay_final+$paypal_fixed+$paypal_var+$cost_price+$postage+$vat_value;
		
		/* PROFIT */
		$profit = $test_price - $total_costs;
		
		/* is it enough? */
		if ($profit > $minimum_profit){
			
			
				$end_price = $test_price;
				$end_price = $end_price+0.01;
		}else{
				$test_price = $test_price+0.01;
		}
		
	
	}while($profit < $minimum_profit);
	
	return '&pound;'.number_format($end_price, 2, '.', '');
	
	
}

function calc_price_amazon($postage, $vat){
	
	/* Variables */
	$end_fee_amazon 		= 0.15;
	$minimum_profit		= 0.1; #post cost - break even - volume wins!
	
	/* Initial Test Price*/
	$cost_price	= $_POST['var_buy_price'];
	$test_price = $cost_price+1;
	$end_price	= $cost_price+10000000000;
	
	do{
		
		/* work out if it's profitable at this price - if not, increase by 1p until you get a profitable price*/
		
		/* Calc Ebay Final value fee*/
		$amazon_final = $test_price*$end_fee_amazon;
		/* VAT */
		$vat_value = $test_price*$vat;
		
		
		/* TOTAL COSTS*/
		$total_costs = $amazon_final+$cost_price+$postage+$vat_value;
		
		/* PROFIT */
		$profit = $test_price - $total_costs;
		
		/* is it enough? */
		if ($profit > $minimum_profit){
			
			
				$end_price = $test_price;
				$end_price = $end_price+0.01;
		}else{
				$test_price = $test_price+0.01;
		}
		
	
	}while($profit < $minimum_profit);
	
	return '&pound;'.number_format($end_price, 2, '.', '');
	
	
}

$standard_postage 		= 0.37;
$large_letter			= 1;
$parcel					= 2.00;

$standard_price			= calc_price($standard_postage, 0);
$letter_price			= calc_price($large_letter, 0);
$parcel_price			= calc_price($parcel, 0);

$standard_price_amazon			= calc_price_amazon($standard_postage, 0);
$letter_price_amazon			= calc_price_amazon($large_letter, 0);
$parcel_price_amazon				= calc_price_amazon($parcel, 0);


$standard_price_vat			= calc_price($standard_postage, 0.2);
$letter_price_vat			= calc_price($large_letter, 0.2);
$parcel_price_vat			= calc_price($parcel, 0.2);

include('../../config.php');  
include('include/includes.php'); 
	/*
$sql = 'SELECT 
			orders.o_id  
		FROM 
			orders, 
			users 
		WHERE 
			orders.o_u_id = users.u_id 
		AND 
			(
				users.u_email like "%'.$_POST['var_search'].'%"
			OR
				orders.	o_amazon_order_id like "%'.$_POST['var_search'].'%"
			)
			
			
		';
		#die($sql);
$query		= mysql_query($sql);
$row		= mysql_fetch_assoc($query);
$num_rows	= mysql_num_rows($query);
if ($num_rows >0){
	do{
		$result .= '
		<tr>
			<td colspan="2">
				<a href="invoices/'.$row['o_id'].'.pdf" target="_blank"><div id="add_to_amazon_and_ebay" style="padding:20px;  font-size:28px; cursor:pointer; background-color:green;color:white;text-align:center;">'.$row['o_id'].'.pdf</div></a>
			</td>
		</tr>
		';
	}while($row	= mysql_fetch_assoc($query));
}else{
	
}*/

$result .= '
	<tr>
			<td colspan="2">
				<div style="padding:20px;  font-size:28px; background-color:white;color:black;text-align:center;">AMAZON Standard: '.$standard_price_amazon.'</div>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				<div style="padding:20px;  font-size:28px; background-color:white;color:black;text-align:center;">AMAZON Large Letter: '.$letter_price_amazon.'</div>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				<div style="padding:20px;  font-size:28px; background-color:white;color:black;text-align:center;">AMAZON Parcel: '.$parcel_price_amazon.'</div>
			</td>
		</tr>





		<tr>
			<td colspan="2">
				<div style="padding:20px;  font-size:28px; background-color:white;color:black;text-align:center;">Ebay Standard: '.$standard_price.'</div>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				<div style="padding:20px;  font-size:28px; background-color:white;color:black;text-align:center;">Ebay Large Letter: '.$letter_price.'</div>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				<div style="padding:20px;  font-size:28px; background-color:white;color:black;text-align:center;">Ebay Parcel: '.$parcel_price.'</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div style="padding:20px;  font-size:28px; background-color:white;color:black;text-align:center; font-weight:bold;">IF VAT APPLIES TO THIS PRODUCT</div>
			</td>
		</tr>
		
		
		<tr>
			<td colspan="2">
				<div style="padding:20px;  font-size:28px; background-color:white;color:black;text-align:center;">Ebay Standard: '.$standard_price_vat.'</div>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				<div style="padding:20px;  font-size:28px; background-color:white;color:black;text-align:center;">Ebay Large Letter: '.$letter_price_vat.'</div>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				<div style="padding:20px;  font-size:28px; background-color:white;color:black;text-align:center;">Ebay Parcel: '.$parcel_price_vat.'</div>
			</td>
		</tr>
		';

?>


	
	
	
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="color:green; font-size: 22px; text-align: left;">
	
	
    
    
		<?php echo $result?>

	
	
    
                   
</table>