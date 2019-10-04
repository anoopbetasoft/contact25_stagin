<?php

include("../../include/config.php");

global $db;

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
		ORDER BY RAND() 
			limit 20
			
			
			'; # Where is the condition of the book #


	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

		
			if ($num_rows>0){
		

			do{
								
				if ($row['s_price_buy_inc_vat']>0){
					$price = $row['s_price_buy_inc_vat'];
					$colour = 'warning';
				}elseif ($row['s_price_lend_inc_vat']>0){
					$price = $row['s_price_lend_inc_vat'];
					$colour = 'info';
				}
				
				print '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="white-box-pd">
						<div class="product-img">
							<img src="https://contact25.com/uploads/7_'.$row['s_s_id'].'.jpg"/> 
                				<div class="pro-img-overlay">
									
								</div>
								
								<div class="product-text">
              				<span class="pro-price bg-'.$colour.'">£'.$price.'</span>
              					<h3 class="box-title m-b-0">'.substr($row['s_label'], 0, 30).'</h3>
              						<small class="text-muted db">'.$row['u_name'].'</small>	
									</div>
									 </div>
									  </div>
									  </div>
									
				';
				
			}while($row = mysqli_fetch_assoc($query));
			
		
		}else{
				
				echo 'Show Neighbours as alternative maybe';
			}

	
?>

	


