<?php 

include_once("../../include/config.php");

global $db;

$sql = '
		DELETE
			FROM
				users_holidays
			WHERE
				users_holidays.uh_id = "'.$_POST['holiday_id'].'"			
		';
//die($sql);
mysqli_query($db,$sql);




 
?>