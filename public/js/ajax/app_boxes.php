<?php 


$hostname_tigers = "88.208.249.28";
$database_tigers = "contact25";
$username_tigers = "contact25-un";
$password_tigers = "mrW09n~8";
$tigers = mysql_pconnect($hostname_tigers, $username_tigers, $password_tigers) or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_select_db($database_tigers, $tigers) or die("Please refresh browser");

session_start();
/* item count */
function item_count ($room, $box){
	$sql = 'SELECT 
				count(*) AS "item_count" 
			FROM
				stock_c25 
			WHERE 
				s_room = "'.$room.'"
			AND
				s_box ="'.$box.'"
			
	';
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	$output = '';
	if ($num_rows>0){
		
		
	
		do{
			
			return($row['item_count']);
			
		}while($row = mysqli_fetch_assoc($query));
		
		
		
	}else{
		return 0;
	}
}
/* boxes*/
function extra_output($sr_id, $i, $name){
	
	$sql = 'SELECT 
				* 
			FROM
				stock_box 
			WHERE 
				stock_box.sb_sr_id ="'.$sr_id.'"
			ORDER BY 
				stock_box.sb_num
			ASC
	';
	
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	$output = '';
	if ($num_rows>0){
		
		if ($i == 0){
				$active = ' active';
			}else{
				$active = '';
			}
		
		$output .= '<div id="vhome'.$sr_id.'" class="tab-pane'.$active.'">
					<div class="col-md-6" style="margin-top:-5px;"><div style="padding-bottom:20px;">
					
					
					<a class="sticon btn-rounded remove_room" data-room_id="'.$sr_id.'" style="color:#fb9678; cursor:pointer;"><i class="ti-trash m-l-5"></i>&nbsp;'.$name.'</a>
					
					
					</div> 
						<ol>';
	
		do{
			
			// color:  #00c292"
			
			$output .= '<li style="font-size: 20px; class="box_select" data-box_value="'.$row['sb_id'].'" data-room_value="'.$row['sb_sr_id'].'"><i class="ti-package box_select" data-box_value="'.$row['sb_id'].'" data-room_value="'.$row['sb_sr_id'].'" style="font-size: 30px; pointer:cursor;"></i>&nbsp;<span style="font-size: 10px">('.item_count($sr_id, $row['sb_id']).')</span></li>';
			
		}while($row = mysqli_fetch_assoc($query));
		
		$output .= '		</ol>
						</div>
					<div class="clearfix"></div>

                </div>';
		
	}
	
	return $output;
}

/* rooms */
	$sql = 'SELECT 
				* 
			FROM 
				stock_room
			WHERE 
				stock_room.sr_u_id = "'.$_SESSION['u_id'].'"
			
			ORDER BY 
				sr_name 
			ASC
	';
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	$output = '';
	$output_extra = '';
	if ($num_rows>0){
		$output .= '<div class="vtabs customvtab">
             	 <ul class="nav tabs-vertical">';
		$i = 0;
		do{
			if ($i == 0){
				$active = ' active';
			}else{
				$active = '';
			}
			$output .= '<li class="tab'.$active.'"><a data-toggle="tab" href="#vhome'.$row['sr_id'].'" aria-expanded="true"> <span class="visible-xs room_'.$row['sr_id'].'">'.$row['sr_name'].'</span> <span class="hidden-xs room_'.$row['sr_id'].'">'.$row['sr_name'].'</span>  </a> </li>';
			$output_extra .= extra_output($row['sr_id'], $i, $row['sr_name']);
			$i ++;
		}while($row = mysqli_fetch_assoc($query));
		$output .= '<li class="tab add_room_show"><a aria-expanded="false" data-toggle="tab" href="#"> <span class="visible-xs ">+ Room</span> <span class="hidden-xs">+ Room</span> </a> </li>
              </ul>
			  
			  <div class="tab-content">
			  '.$output_extra.'
			  </div>';
		
	}else{
		echo json_encode(array("-1")); // no rooms set up yet
		die();
	}

/*
$output = '
				 
                
                
              <div class="tab-content">
                <div id="vhome3" class="tab-pane active">
					<div class="test_this"><div class="col-md-6"><ol><li style="font-size: 20px; color:  #00c292" class="box_1_1"><i class="ti-package box_1_1" style="font-size: 20px; color:  #00c292"></i></li><li style="font-size: 20px; " class="box_1_2"><i class="ti-package box_1_2" style="font-size: 20px;"></i></li><li style="font-size: 20px; display: none;" class="box_1_3"><i class="ti-package box_1_3" style="font-size: 20px; display: none;"></i></li><li style="font-size: 20px; display: none;" class="box_1_4"><i class="ti-package box_1_4" style="font-size: 20px; display: none;"></i></li></ol></div></div>
                 <div class="clearfix"></div>
                </div>
                <div id="vprofile3" class="tab-pane">
                 <ol>
                   		<li><i class="ti-package" style="font-size: 20px;"></i></li>
                   		<li><i class="ti-package" style="font-size: 20px;"></i></li>
                   		<li><i class="ti-package" style="font-size: 20px;"></i></li>
                   		<li><i class="ti-package" style="font-size: 20px;"></i></li>
                    </ol>
                </div>
                <div id="vmessages3" class="tab-pane">
                 <ol>
                   		<li><i class="ti-package" style="font-size: 20px;"></i></li>
                   		<li><i class="ti-package" style="font-size: 20px;"></i></li>
                   		<li><i class="ti-package" style="font-size: 20px;"></i></li>
                   		<li><i class="ti-package" style="font-size: 20px;"></i></li>
                    </ol>
                </div>
              </div>
            </div>';*/


echo json_encode(array($output));
die();



	if ($_SESSION['u_id'] == 1){
		$extra_sql = ' OR order_details.od_purchased_via = 0';
	}

	$sql = 'select sum(order_details.od_price) as "sales_total" from order_details, orders WHERE (order_details.od_purchased_via = '.$_SESSION['u_id'].' '.$extra_sql.')
AND order_details.od_o_id = orders.o_id
AND orders.o_dispatched >= (CURDATE() - INTERVAL 1 MONTH )';
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	if ($num_rows>0){
		do{
			$earnings_last_30_days = '£'.$row['sales_total'];
		}while($row = mysqli_fetch_assoc($query));
	}

if ($_SESSION['u_id']>0){
	echo json_encode(array($u_name, $earnings_last_30_days));
}


die();



?>