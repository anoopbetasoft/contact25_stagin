<?php 

include("../../include/config.php");

global $db; 

	$sql = '
			SELECT 
				*
			FROM 
				stock_box
			WHERE 
				stock_box.sb_sr_id =  "'.$_POST['filter_by_room'].'"
			ORDER BY
				sb_num ASC
			';	

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

		if ($num_rows>0){
		
			$i = 1;
			
				$boxes .= ' 
								<a class="dropdown-item dropdown-box" href="javascript:void(0)" data-box-id="0"> All Boxes</a>

								';
			do{
				$boxes .= ' 
								<a class="dropdown-item dropdown-box" href="javascript:void(0)" data-box-id="'.$row['sb_id'].'">'.$row['sb_num'].'</a>
										
								';

				
				$i ++;
			}while($row = mysqli_fetch_assoc($query));
			
			
				print'
					<button type="button"class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="ti-package"></i> <span  id="box_label"> All Boxes</span>
					</button>
							<div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 34px, 0px); top: 0px; left: 0px; will-change: transform;">
								'.$boxes.'
							</div>
						
						';
			
			
				
			//echo "#".$_SESSION['limit_start'];
		
		}else{
				
				echo 'Please go to add products for more boxes' ;
			}

?>

              
						
						    
