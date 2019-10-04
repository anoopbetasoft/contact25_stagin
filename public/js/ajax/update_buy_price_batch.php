<?php
include('../../config.php');  
include('include/includes.php'); 

if ($_SESSION['u_id'] == 22212){
	
	$s_id = $_POST['order_item_id'];
	
	$sql = 'SELECT 
				s_price
			FROM 
				stock_c25
			WHERE 
				stock_c25.s_s_id = "'.$_POST['order_item_id'].'"
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
	
	
	$sql = 'UPDATE
					stock_c25 
				SET  
					s_price_buy = "'.$original_buy_price.'",
					s_price_buy_inc_vat = "'.$buy_price_inc_vat.'",
					s_price_buy_delivery = "'.$delivery.'"
				WHERE 
					s_s_id = "'.$s_id.'"
				AND 
					stock_c25.s_u_id = 22212
				';
	
	mysql_query($sql);
	
	$sql = 'UPDATE
					spares 
				SET  
					s_price = "'.(($buy_price_inc_vat+$delivery)*1.2).'"
				WHERE 
					s_id = "'.$s_id.'"
				';
	
	mysql_query($sql);
	
	$sql = 'UPDATE
					spares_1 
				SET  
					s_price = "'.(($buy_price_inc_vat+$delivery)*1.2).'"
				WHERE 
					s_id = "'.$s_id.'"
				';
	
	mysql_query($sql);
	
	

}



?>


	