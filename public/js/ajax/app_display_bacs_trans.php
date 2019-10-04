<?php 

include("../../include/config.php");

global $db;
	
$sql = 'SELECT
SUM(credits_debits.cd_value) AS "money_cleared",cd_bank_acc,cd_timestamp
FROM
credits_debits
WHERE
credits_debits.cd_u_id = "'.$_SESSION['c25_id'].'"
AND
credits_debits.cd_o_id > 0
AND (
credits_debits.cd_timestamp < DATE_ADD(NOW(), INTERVAL -30 DAY)
OR
credits_debits.cd_value < 0
      )
';

	  
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	
		$money_cleared = $row['money_cleared'];
		$bank_acc = $row['cd_bank_acc'];
		$date = date("D, d F Y", strtotime($row['cd_timestamp']));

?>

                             
                BACS Transfer £<?=$money_cleared?> to Acc: <?=$bank_acc?>  (<?=$date?>)