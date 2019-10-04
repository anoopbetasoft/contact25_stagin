<?php 

include_once("../../include/config.php");

global $db;

$sql = '
		DELETE
			FROM
				users_opening_hrs
			WHERE
				users_opening_hrs.uoh_id = "'.$_POST['time_id'].'"			
		';
mysqli_query($db,$sql);





?>