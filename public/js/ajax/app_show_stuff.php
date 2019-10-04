<?php 
$hostname_tigers = "88.208.249.28";
$database_tigers = "contact25";
$username_tigers = "contact25-un";
$password_tigers = "mrW09n~8";
$tigers = mysql_pconnect($hostname_tigers, $username_tigers, $password_tigers) or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_select_db($database_tigers, $tigers) or die("Please refresh browser");
session_start();


$sql = 'SELECT 
			s_room, 
			stock_room.sr_name, 
			count(stock_c25.s_id) AS "count" 
		FROM
			stock_c25, 
			stock_room 
		WHERE 
			stock_c25.s_u_id = 1
		AND 
			stock_c25.s_room = stock_room.sr_id
 		GROUP BY 
			stock_c25.s_room
						
			';
				
			$query = mysqli_query($db,$sql);
			$row = mysqli_fetch_assoc($query);
			$num_rows = mysqli_num_rows($query);
			$output = '';
			$i = 0;
			if ($num_rows>0){
				do{
					$input = array("purple", "success", "info", "warning", "danger");
					$rand_keys = array_rand($input, 2);

					
					#$colour = shuffle(array("purple", "success", "info"));
					$output .= ' <div class="col-md-3 col-xs-12 col-sm-6 room_click" data-room="'.$row['s_room'].'" style="cursor:pointer;">
							  <div class="white-box text-center bg-'.$input[$rand_keys[$i]].'">
								<h1 class="text-white counter">'.$row['count'].'</h1>
								<p class="text-white">'.$row['sr_name'].'</p>
							  </div>
							</div>';
					$i ++;
				}while($row = mysqli_fetch_assoc($query));
			}

$sql = 'SELECT 
			s_room,
			count(stock_c25.s_id) AS "count" 
		FROM
			stock_c25
		WHERE 
			stock_c25.s_u_id = 1
		AND
			stock_c25.s_room = 0
 		GROUP BY 
			stock_c25.s_room
						
			';
				
			$query = mysqli_query($db,$sql);
			$row = mysqli_fetch_assoc($query);
			$num_rows = mysqli_num_rows($query);
			
			if ($num_rows>0){
				do{
					$input = array("purple", "success", "info", "warning", "danger");
					$rand_keys = array_rand($input, 2);
					
					
					#$colour = shuffle(array("purple", "success", "info"));
					$output .= ' <div class="col-md-3 col-xs-12 col-sm-6 room_click" style="cursor:pointer;" data-room="'.$row['s_room'].'">
							  <div class="white-box text-center bg-'.$input[$rand_keys[0]].'">
								<h1 class="text-white counter">'.$row['count'].'</h1>
								<p class="text-white">Put these products in a room</p>
							  </div>
							</div>';
					$i ++;
				}while($row = mysqli_fetch_assoc($query));
			}


## 2,6,9



$dropdown_menu = '<div class="row">
       '.$output.'
      </div>
	  
	  <div id="rooms"></div>
	  ';

echo $dropdown_menu; 




?>