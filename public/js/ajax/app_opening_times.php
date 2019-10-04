<?php 

include("../../include/config.php");

global $db;
	
	$sql = 'SELECT
			*
		FROM
			users_opening_hrs
		WHERE
			users_opening_hrs.uoh_u_id = "'.$_SESSION['c25_id'].'"		
		Order by
			users_opening_hrs.uoh_day 
		ASC			
			';
  
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	if ($num_rows>0){
			
			$output = '';
		
			do{
					
					if ($row['uoh_day']==1){
						$day = 'Mon';
						}
					if ($row['uoh_day']==2){
							$day = 'Tue';
							}
					if ($row['uoh_day']==3){
							$day = 'Wed';
							}
					if ($row['uoh_day']==4){
							$day = 'Thu';
							}
					if ($row['uoh_day']==5){
							$day = 'Fri';
							}
					if ($row['uoh_day']==6){
							$day = 'Sat';
							}
					if ($row['uoh_day']==7){
							$day = 'Sun';
							}
					
			
					$open_time = date("H:ia", strtotime($row['uoh_start_time']));
					$close_time = date("H:ia", strtotime($row['uoh_end_time']));
					$output .=
						$day.' '.$open_time.' - '.$close_time.'
						<span class="remove-opening-time" style="color:red; font-size:10px; cursor: pointer;" data-time_id="'.$row['uoh_id'].'">(remove)
						</span>
						</br>
						';
			}while($row = mysqli_fetch_assoc($query));
			
			
		}

?>

               <?=$output?>
            
