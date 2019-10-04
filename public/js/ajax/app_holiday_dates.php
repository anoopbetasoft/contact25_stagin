<?php

include("../../include/config.php");

global $db; 





$format_date = $_POST['date2'];
$format_date =  str_replace("T"," ", $format_date);
$format_date =  explode(".", $format_date);
$format_date = $format_date[0];

$format_date1 = $_POST['date1'];
$format_date1 =  str_replace("T"," ", $format_date1);
$format_date1 =  explode(".", $format_date1);
$format_date1 = $format_date1[0];


$sql = 'INSERT INTO 
			users_holidays
		(
			uh_u_id,
			uoh_start_time,
			uoh_end_time
		)
			VALUES 
		(
			"'.$_SESSION['c25_id'].'",
			'.$format_date1.'",
			'.$format_date.'"
		)
';
//
mysqli_query($db,$sql);
//die($sql);
?>