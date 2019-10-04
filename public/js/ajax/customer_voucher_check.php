<?php 

include ("../../config.php");


$sql = "SELECT u_id FROM users WHERE u_email = '".$_POST['u_email']."'";
$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);


if ($num_rows>0){
	/*Existing Customer - give free Delivery if ordering 5 or more stickers*/	
	if ($_POST['sticker_qty']>4){
		echo 0.5;
		
		
	}
}


?>