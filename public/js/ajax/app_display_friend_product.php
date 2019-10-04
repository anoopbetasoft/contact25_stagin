<?php

include("../../include/config.php");

global $db;

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
			s_desc,
			s_label,
			s_condition,
			s_u_id,
			s_qty,
			s_sell_f,
			s_sell_n,
			s_sell_c,
			s_lend_f,
			s_lend_n,
			s_lend_c,
			s_price_lend_inc_vat, 
			s_lend_dwmy,
			users.u_name
		FROM
			stock_c25,
			users_friends,
			users
		WHERE
			stock_c25.s_u_id = users_friends.uf_u_id_2
		AND
			users.u_id = users_friends.uf_u_id_2
		AND
			users_friends.uf_u_id = "'.$_SESSION['c25_id'].'"
		AND
			stock_c25.s_qty > 0
		AND
			(
				stock_c25.s_price_buy_inc_vat > 0
			or
				stock_c25.s_price_lend_inc_vat > 0
			)
		AND
			stock_c25.s_s_id > 0
		AND
			stock_c25.s_label like "%'.$_SESSION['search'].'%"		
	
			
			
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
			s_desc,
			s_label,
			s_condition,
			s_u_id,
			s_qty,
			s_sell_f,
			s_sell_n,
			s_sell_c,
			s_lend_f,
			s_lend_n,
			s_lend_c,
			s_price_lend_inc_vat, 
			s_lend_dwmy,
			users.u_name
		FROM
			stock_c25,
			users_friends,
			users
		WHERE
			stock_c25.s_u_id = users_friends.uf_u_id_2
		AND
			users.u_id = users_friends.uf_u_id_2
		AND
			users_friends.uf_u_id = "'.$_SESSION['c25_id'].'"
		AND
			stock_c25.s_qty > 0
		AND
			(
				stock_c25.s_price_buy_inc_vat > 0
			or
				stock_c25.s_price_lend_inc_vat > 0
			)
		AND
			stock_c25.s_s_id > 0
		AND
			stock_c25.s_label like "%'.$_SESSION['search'].'%"	
		'.$_SESSION['extra_sort'].'
			limit '.$_SESSION['limit_start'].',12
			
			
			'; # Where is the condition of the book #


	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

		
			if ($num_rows>0){
		

			$i = 0;
			
			do{
				if ($i == 0){
					$active = ' active';
				}else{
					$active = '';
				}
				
				if ($row['s_sell_f'] == 1){
					if ($row['s_price_buy_inc_vat']>0){
						$sell_price_display = '<span class="text-info" style="font-size:16px;"><i class="fa fa-tag"></i>&nbsp; £'.$row['s_price_buy_inc_vat'].'</span> &nbsp;&nbsp;';
					}else{
						$sell_price_display = '<span class="text-info" style="font-size:16px;"><i class="fa fa-tag"></i>&nbsp; FREE</span> &nbsp;&nbsp;';
					}
					
				}else{
					$sell_price_display = '';
				}
				
				if ($row['s_lend_f'] == 1){
					
					if ($row['s_lend_dwmy'] == 1){
						$duration = 'day';
					}
					if ($row['s_lend_dwmy'] == 2){
						$duration = 'wk';
					}
					if ($row['s_lend_dwmy'] == 3){
						$duration = 'mth';
					}
					if ($row['s_lend_dwmy'] == 4){
						$duration = 'yr';
					}
					
					
					if ($row['s_price_lend_inc_vat'] == 0){
						$lend_display_price = 'FREE';
					}else{
						$lend_display_price = $row['s_price_lend_inc_vat'];
					}
					
					if (['s_price_lend_inc_vat']>0){
						$lend_price_display = '<span class="text-warning" style="font-size:16px;"><i class="fa fa-refresh m-l-5"></i>&nbsp; £'.$lend_display_price.'/'.$duration.'</span> &nbsp;&nbsp;';
					}else{
						$lend_price_display = '<span class="text-warning" style="font-size:16px;"><i class="fa fa-refresh m-l-5"></i>&nbsp; £FREE /'.$duration.'</span> &nbsp;&nbsp;';
					}
					//$lend_price_display = '<span class="buy-page" data-s_id="'.$row['s_id'].'"><button class="btn btn-block btn-warning btn-rounded buy_now" data-office_depot_id="799821" style="margin-bottom:-20%"><i class="fa fa-refresh m-l-5"></i> Lend it</button></span>';
				}else{
					$lend_price_display = '';
				}
				
				print '<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
							<div class="white-box-pd buy-page" data-s_id="'.$row['s_id'].'">
								<div class="product-img">
									<img src="https://contact25.com/uploads/7_'.$row['s_s_id'].'.jpg"/> 
                				<span style="float:right;">
												'.$sell_price_display.$lend_price_display.'
								
								<div class="product-text">
              							<h3 class="box-title m-b-0">'.substr($row['s_label'], 0, 30).'</h3>
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
				
				echo '
				<div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                This is some text within a card block.
                            </div>
                        </div>
                    </div>
                </div>' ;
			}

	
?>

	


