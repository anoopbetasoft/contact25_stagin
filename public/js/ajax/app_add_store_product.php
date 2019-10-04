<?php 
$hostname_tigers = "88.208.249.28";
$database_tigers = "contact25";
$username_tigers = "contact25-un";
$password_tigers = "mrW09n~8";
$tigers = mysql_pconnect($hostname_tigers, $username_tigers, $password_tigers) or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_select_db($database_tigers, $tigers) or die("Please refresh browser");

#$sql = 'UPDATE test SET value = "++marker"';
#mysqli_query($db,$sql);

session_start();

if ($_POST['item_condition']==1){
	/* new */
	$condition 		= 11;
	$price 			= $_POST['price_new']+$_POST['post_cost_new']+$_POST['fee_cost_new'];
	$buy_price 		= $_POST['price_new'];
	$delivery 		= $_POST['post_cost_new'];
}
if ($_POST['item_condition']==2){
	/* good */
	$condition = 3;
	$price 			= $_POST['price_good']+$_POST['post_cost_good']+$_POST['fee_cost_new'];
	$buy_price 		= $_POST['price_good'];
	$delivery 		= $_POST['post_cost_good'];
}
if ($_POST['item_condition']==3){
	/* ok */
	$condition = 4;
	$price 			= $_POST['price_ok']+$_POST['post_cost_ok']+$_POST['fee_cost_new'];
	$buy_price 		= $_POST['price_ok'];
	$delivery 		= $_POST['post_cost_ok'];
}


$sql = '
		INSERT INTO 
			stock_c25
		(
			s_u_id,
			s_session_id,
			s_s_id,
			s_pic_link,
			s_label,
			s_qty,
			s_weight,
			s_height,
			s_length,
			s_width,
			s_condition,
			s_price,
			s_price_buy,
			s_price_buy_inc_vat,
			s_price_buy_delivery,
			s_barcode_type,
			s_ISBN13,
			s_ISBN10,
			s_price_updated,
			s_box,
			s_room
		)
			VALUES 
		(
			"'.$_SESSION['u_id'].'",
			"'.session_id().'",
			"'.$_POST['s_id'].'",
			"http://contact25.com/uploads/7_'.$_POST['s_id'].'.jpg",
			"'.html_entity_decode ($_POST['s_label']).'",
			"'.$_POST['s_qty'].'",
			"'.$_POST['s_weight'].'",
			"'.$_POST['s_height'].'",
			"'.$_POST['s_length'].'",
			"'.$_POST['s_width'].'",
			"'.$condition.'",
			"'.$price.'",
			"'.$buy_price.'",
			"'.$buy_price.'",
			"'.$delivery.'",
			"4",
			"'.$_POST['s_ISBN13'].'",
			"'.$_POST['s_ISBN10'].'",
			"'.date("Y-m-d H:i:s").'",
			"'.$_POST['box_location'].'",
			"'.$_POST['room_location'].'"
		)
';
mysqli_query($db,$sql);
#$sql = 'UPDATE test SET value = "marker_sql++'.addslashes($sql).'"';
#mysqli_query($db,$sql);

	

?>