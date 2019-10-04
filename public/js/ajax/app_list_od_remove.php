<?php 
include_once("../../include/config.php");

$sql = 'UPDATE
			stock_c25
		SET
			s_amazon_listed = -1
		WHERE
			s_u_id = 22212
		AND
			s_id = "'.$_POST['item_id'].'"

';
#$sql = "UPDATE test SET value = '".$sql."'";
mysqli_query($db,$sql);


echo $_POST['item_id'];

?>