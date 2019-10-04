<?php 

include_once("../../include/config.php");

$sql = 'UPDATE test SET value = "123"';
mysqli_query($db,$sql);

$sql = 'INSERT INTO 
			orders
		(
			o_u_id,
			o_name,
			o_email,
			o_address_del_1,
			o_address_del_2,
			o_address_del_4,
			o_country,
			o_currency,
			o_paid
		)
			VALUES 
		(
			"1",
			"Antony Vila",
			"ebay@contact25.com",
			"56 Weetwood Lane",
			"Leeds",
			"West Yorkshire",
			"15",
			"GBP",
			"1"
		)
';

mysqli_query($db,$sql);

//
$order_details = order_details($_POST['product_id']);

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
			od_purchased_for,
			od_ebay_item_ID,
			od_purchased_via
		)
			VALUES 
		(
			"'.lastorderid().'",
			"'.$order_details[0].'",
			"'.$_POST['product_id'].'",
			"'.$_POST['product_qty'].'",
			"1",
			"7",
			"'.$order_details[1].'",
			"1",
			"22212"
		)
';
//$sql = 'UPDATE test SET value = "'.$sql.'"';"'.$_POST['s_length'].'",
mysqli_query($db,$sql);


function lastorderid(){
	
	$sql = 'SELECT o_id FROM orders WHERE orders.o_u_id = 1 ORDER BY orders.o_id DESC LIMIT 1';
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	$output = array();

	if ($num_rows>0){
		do{
			
		
			return $row['o_id'];
			
			
		}while($row = mysqli_fetch_assoc($query));
	}
}


function order_details($s_id){
	
	$sql = 'select * from stock_c25 WHERE stock_c25.s_s_id = "'.$s_id.'"';
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	if ($num_rows>0){
		do{
			
		
			return array($row['s_label'], $row['s_price_buy']);
			
			
		}while($row = mysqli_fetch_assoc($query));
	}
}
	

?>