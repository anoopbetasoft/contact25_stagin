<?php 

include_once("../../include/config.php");
global $db;

$sql = 'INSERT INTO 
			users_opening_hrs 
				(
					uoh_u_id, 
					uoh_day, 
					uoh_start_time,
					uoh_end_time
				) 
		VALUES 
				( 
					"1",
					"'.$_POST['weekday'].'",
					"'.str_replace("-",":",$_POST['hours_start']).':00",
					"'.str_replace("-",":",$_POST['hours_end']).':00"
				)
			
			
			'; 


mysqli_query($db,$sql);

?>