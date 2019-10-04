<?php
include('../../config.php');  
include('include/includes.php'); 

if ($_SESSION['u_id'] == 22212){
	
	
	$sql = 'SELECT 
				order_details.od_s_id
			FROM 
				order_details
			WHERE 
				od_id = "'.$_POST['order_item_id'].'"
			';
	
	$query		= mysql_query($sql);
	$row		= mysql_fetch_assoc($query);
	$num_rows	= mysql_num_rows($query);

	if ($num_rows >0){
		do{
			$s_id = $row['od_s_id'];
		}while($row	= mysql_fetch_assoc($query));
	}
	
	$sql = 'SELECT 
				s_price
			FROM 
				stock_c25
			WHERE 
				stock_c25.s_s_id = "'.$s_id.'"
			';
	
	$query		= mysql_query($sql);
	$row		= mysql_fetch_assoc($query);
	$num_rows	= mysql_num_rows($query);

	if ($num_rows >0){
		do{
			$viking_price = $row['s_price'];
		}while($row	= mysql_fetch_assoc($query));
	}
	
	
	$original_buy_price = $_POST['value'];
	$buy_price_inc_vat = $original_buy_price*1.2;
	if ($_POST['value'] < 24.99){
		$delivery = 2.95*1.2;	
	}else{
		$delivery = 0;	
	}
	
	/* should the order to go office depot or viking direct? */
	
	if ($viking_price < $buy_price_inc_vat){
		/* go to viking*/	
		$sql = 'UPDATE
					order_details 
				SET  
					 od_purchased_via = "22806"
				WHERE 
					od_id = "'.$_POST['order_item_id'].'"
				';
		mysql_query($sql);
		$original_buy_price = $viking_price/1.2;
		$buy_price_inc_vat = $viking_price;
		if ($buy_price_inc_vat < 24.99){
			$delivery = 2.95*1.2;	
		}else{
			$delivery = 0;	
		}
	}else{
		/* stay with office depot*/	
		
	}
	
	
	
	$sql = 'UPDATE
					stock_c25 
				SET  
					s_price_buy = "'.$original_buy_price.'",
					s_price_buy_inc_vat = "'.$buy_price_inc_vat.'",
					s_price_buy_delivery = "'.$delivery.'"
				WHERE 
					s_s_id = "'.$s_id .'"
				AND 
					stock_c25.s_u_id = 22212
				';
	
	mysql_query($sql);
	
	$sql = 'UPDATE
					spares 
				SET  
					s_price = "'.(($buy_price_inc_vat+$delivery)*1.2).'"
				WHERE 
					s_id = "'.$s_id .'"
				';
	
	mysql_query($sql);
	
	$sql = 'UPDATE
					spares_1 
				SET  
					s_price = "'.(($buy_price_inc_vat+$delivery)*1.2).'"
				WHERE 
					s_id = "'.$s_id .'"
				';
	
	mysql_query($sql);
	
	/*
		SPECIAL PRICE UPDATES (FROM OFFICE DEPOT)
	*/
	$sql = 'UPDATE
					order_details 
				SET  
					 od_purchased_for = "'.($buy_price_inc_vat+$delivery).'"
				WHERE 
					od_id = "'.$_POST['order_item_id'].'"
				';
	mysql_query($sql);

}else{
	
	/*
		STANDARD UPDATES OF BUY PRICES - BUYING FROM AMAZON
	*/

		$sql = 'UPDATE
					order_details 
				SET  
					 od_purchased_for = "'.$_POST['value'].'"
				WHERE 
					od_id = "'.$_POST['order_item_id'].'"
				';
			
		mysql_query($sql);
	#echo $sql; 
	
	$sql = 'SELECT 
				orders.o_locked_until,
				order_details.*
			FROM 
				order_details,
				orders
			WHERE 
				o_locked_until > "'.date('Y-m-d 00:00:00').'"
			AND
				orders.o_id = order_details.od_o_id
			AND
				order_details.od_purchased_for > 0
			';
	
	$query		= mysql_query($sql);
	$row		= mysql_fetch_assoc($query);
	$num_rows	= mysql_num_rows($query);
	
	$profit = 0;
	if ($num_rows >0){
		do{
			$od_price = $row['od_price'];
			$od_purchased_for = $row['od_purchased_for'];
			
			$ebay_fee = $od_price * 0.085;
			$paypal_fee = $od_price * 0.025;
			$paypal_fixed = 0.2;
			$total_fees = $ebay_fee+$paypal_fee+$paypal_fixed;
			
			$sub_total_profit = $od_price-$od_purchased_for-$total_fees;
			$profit = $profit+$sub_total_profit;
			
			
		}while($row	= mysql_fetch_assoc($query));
	}
	if ($profit < 0){
		echo '<span style="color:red">';	
	}else{
		echo '<span style="color:green">';		
	}
	echo '£'.number_format($profit, 2, '.', '');
	echo '</span>';	
}



?>


	