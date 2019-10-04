<?php 

include("../../include/config.php");

global $db;

$sql = 'SELECT
SUM(credits_debits.cd_value) AS "money_pending"
FROM
credits_debits
WHERE
credits_debits.cd_u_id = "'.$_SESSION['c25_id'].'"
AND
credits_debits.cd_o_id > 0
AND
      credits_debits.cd_timestamp > DATE_ADD(NOW(), INTERVAL -30 DAY)';
	  
	 
	  
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	
	$pending_money = $row['money_pending'];

$sql = 'SELECT 
			*
		FROM
			currency
		WHERE
			currency.c_id = 1
		';	

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	$currency_sign = $row['c_display']
	

?>

                
                   
                <?=$currency_sign?><?=$pending_money?>
           
