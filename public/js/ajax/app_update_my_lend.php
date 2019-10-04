<?php 

include("../../include/config.php");

global $db;

$sql = '
		UPDATE
			stock_c25
		SET
			stock_c25.s_price_lend_inc_vat = "'.$_POST['lend_val'].'"
		WHERE
			stock_c25.s_id = "'.$_POST['s_id'].'"
		
		';
 
mysqli_query ($db,$sql);

?>