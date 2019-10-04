<?php 
$output = array(0,0,4,6,8,5,6,4,8,6,6,0);

echo json_encode($output);

die();

include_once("../../include/config.php");

$sql = 'UPDATE
			stock_c25
		SET
			s_amazon_listed = 1
		WHERE
			s_u_id = 22212
		AND
			s_id = "'.$_POST['item_id'].'"

';
#$sql = "UPDATE test SET value = '".$sql."'";
mysqli_query($db,$sql);

$minimum_price = $_POST['min_selling'];
if ($_POST['format_item'] == 'LL'){
	$minimum_price = $minimum_price - 1.4;
}

$sql = 'UPDATE
			spares
		SET
			s_cheapest_amazon = "'.$_POST['initial_list_price'].'",
			s_min_price = "'.$minimum_price.'",
			s_cat = "OTHER",
			s_cheapest_check = "2000:00:00 00:00:00",
			s_price = "'.$_POST['initial_list_price'].'",
			s_format = "'.$_POST['format_item'].'",
			s_qty = "'.$_POST['s_qty'].'"
		WHERE
			s_u_id = 22212
		AND
			s_id = "'.$_POST['s_s_id'].'"

';
#mysqli_query($db,$sql);
#$sql = "UPDATE test SET value = '".$sql."'";
mysqli_query($db,$sql);	

echo $_POST['item_id'];

?>