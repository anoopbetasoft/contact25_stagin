<?php

include("../../include/config.php");

global $db;
	

		$_SESSION['search_my_stuff'] = $_POST['search_my_stuff'];
	
	
	if (strlen($_POST['sort_by'])>0){
		$_SESSION['extra_sort'] = 'ORDER BY '.$_POST['sort_by'];
		$_SESSION['limit_start'] = 0;
	}

	if (empty($_SESSION['limit_start'])){
		
		$_SESSION['limit_start'] = 0;
		
	}
	
	if ($_POST['limit_start']>-1){
		
		$_SESSION['limit_start'] = $_POST['limit_start'];
	}

	if ($_POST['limit_start'] == -1){
		
		$_SESSION['limit_start'] = $_SESSION['limit_start']-12;
		//die("test");
	}

	if ($_POST['limit_start'] == -2){
		
		$_SESSION['limit_start'] = $_SESSION['limit_start']+12;
	}

	if ($_SESSION['limit_start'] < 0){
		
		$_SESSION['limit_start'] = 0;
	}


	// find the total results for pagination
$sql = '
  		SELECT 
			s_id, 
			s_s_id, 
			s_price_buy_inc_vat, 
			s_desc, s_label, 
			s_condition, 
			s_u_id, 
			s_qty, 
			users.u_name 
		FROM 
			stock_c25, users 
		WHERE 
			stock_c25.s_u_id = users.u_id
		AND
			users.u_id = "'.$_SESSION['c25_id'].'"
		AND
			stock_c25.s_label like "%'.$_SESSION['search_my_stuff'].'%"
		Limit 200
			
			
			'; # Where is the condition of the book #


	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	$total_rows_for_pagination = $num_rows;

  $sql = '
  		SELECT 
			s_id, 
			s_s_id, 
			s_price_buy_inc_vat, 
			s_desc, s_label, 
			s_condition, 
			s_u_id, 
			s_qty, 
			users.u_name 
		FROM 
			stock_c25, users 
		WHERE 
			stock_c25.s_u_id = users.u_id
		AND
			stock_c25.s_label like "%'.$_SESSION['search_my_stuff'].'%"
		AND
			users.u_id = "'.$_SESSION['c25_id'].'"
		'.$_SESSION['extra_sort'].'
			limit '.$_SESSION['limit_start'].',12
			
			
			'; # Where is the condition of the book #


	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

		
			if ($num_rows>0){
		

			do{
								
				if ($row['s_price_buy_inc_vat']>0){
					$price = $row['s_price_buy_inc_vat'];
					$colour = 'info';
				}elseif ($row['s_price_lend_inc_vat']>0){
					$price = $row['s_price_lend_inc_vat'];
					$colour = 'warning';
				}
				
				print '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="white-box-pd">
						<div class="product-img">
							<img src="https://contact25.com/uploads/7_'.$row['s_s_id'].'.jpg"/> 
                				<div class="pro-img-overlay">
									<a href="javascript:void(0)" class="bg-info">
										<i class="ti-marker-alt"></i>
									</a> 
									<a href="javascript:void(0)" class="bg-danger">
										<i class="ti-trash"></i>
									</a>
								</div>
								
								<div class="product-text">
              				<span class="pro-price bg-'.$colour.'">£'.$price.'</span>
              						<small class="text-muted db">'.$row['u_name'].'</small>	
									</div>
									 </div>
									  </div>
									  </div>
									
				';
				 
			}while($row = mysqli_fetch_assoc($query));
			
				$page_numbers = floor($total_rows_for_pagination/12);
				$_SESSION['limit_start'];
				
				if ($page_numbers>0){
					
					$i=0;
					do{
						if (($i==0)&&($_SESSION['limit_start']==0)){
							$active = 'active';
							$active_colour = 'btn-info';
						}else{
							
							$check = ($_SESSION['limit_start']/12);
							
							if ($check == $i){
								$active = ' active';
								$active_colour = 'btn-info';
							}else{
								$active = '';
								$active_colour = 'btn-secondary';
							}
						}
						
						
						
						
						$page_numbers_show_new .= '<button type="button" class="btn '.$active_colour.' page_change" data-pagination_change="'.($i*12).'"><a>'.($i+1).'</a></button>';
						
					$i ++;	
					}while($i<$page_numbers);
						echo '<div style="clear:both;"></div>
						
							<button type="button" class="btn btn-secondary page_change fa fa-angle-left" data-pagination_change="-1"></button>
												'.$page_numbers_show_new.'
                                                                                 
                           <button type="button" class="btn btn-secondary page_change fa fa-angle-right" data-pagination_change="-2"></button>
						
						
					';
				}
				
				
			//echo "#".$_SESSION['limit_start'];
		
		}else{
				
				echo 'Need to add product' ;
			}

	
?>

	


