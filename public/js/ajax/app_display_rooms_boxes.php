<?php 

include("../../include/config.php");

global $db; 

/*$sql = '
  		SELECT 
			s_id, 
			s_s_id, 
			s_price_buy_inc_vat, 
			s_desc, s_label, 
			s_condition, 
			s_u_id, 
			s_qty, 
			users.u_name,
			stock_room.sr_id,
			stock_room.sr_name
		FROM 
			stock_c25, users, stock_room
		WHERE 
			stock_c25.s_u_id = users.u_id
		AND 
			stock_c25.s_room = stock_room.sr_id
		AND
			users.u_id = "'.$_SESSION['c25_id'].'"
		AND
			stock_room.sr_id = "'.$_POST['room-freq'].'"
		Limit 200

			
			'; # Where is the condition of the book #
*/

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	$sql = '
			SELECT 
				*
			FROM 
				stock_room
			WHERE 
				stock_room.sr_u_id =  "'.$_SESSION['c25_id'].'"
			';	

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

		if ($num_rows>0){
		
			
			
				$room_names .= ' 
								<a class="dropdown-item dropdown-rooms-duration" href="javascript:void(0)" data-room-id="0">&raquo; All Rooms</a>

								';
			do{
				$room_names .= ' 
								<a class="dropdown-item dropdown-rooms-duration" href="javascript:void(0)" data-room-id="'.$row['sr_id'].'">'.$row['sr_name'].'</a>
										
								';

				
				
			}while($row = mysqli_fetch_assoc($query));
			
			
				print'
					<button type="button" id="room_duration_label" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="mdi mdi-filter-outline"></i> All Rooms
					</button>
							<div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 34px, 0px); top: 0px; left: 0px; will-change: transform;">
								'.$room_names.'
							</div>
						
						';
			
			
				
			//echo "#".$_SESSION['limit_start'];
		
		}else{
				
				echo 'Please go to add products for more boxes' ;
			}

?>

              
						
						    
