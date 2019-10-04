<?php 

include("../../include/config.php");

global $db;

$sql = '
		UPDATE
			stock_c25
		SET
			stock_c25.s_qty = "'.$_POST['qty_val'].'"
		WHERE
			stock_c25.s_id = "'.$_POST['s_id'].'"
		
		';

mysqli_query ($db,$sql);

?>