<?php 
include('../../config.php'); 

$sql = 'select 
			
			cart.ct_s_id
		from 
			cart 
		WHERE 
			cart.ct_session = "'.session_id().'"
		';
#die($sql);
$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);
$sub_total_post = '';

if ($num_rows>0){

	do{
		
		$sub_total_post = $sub_total_post + calc_int_post($row['ct_s_id']);
				
	}while($row = mysql_fetch_assoc($query));
}
session_start();
if ($_POST['c_id'] == 15){
	echo '<tr class="shipping">
											<th>FREE DELIVERY</th>
											<td>&nbsp;</td>
										</tr>
										<tr class="order-total">
											<th>Order Total</th>
											<td><strong><span class="amount">£'.$_POST["sub_total"].'</span></strong>
											
											</td>
										</tr>		
	
	
	';
}else{
	echo '<tr class="shipping">
											<th>DELIVERY CHARGE</th>
											<td>£'.number_format($sub_total_post, 2, '.', '').'</td>
										</tr>
										
										<tr class="shipping">
											<th style="color:red;">DELIVERY TIMES</th>
											<td style="font-size:10px;color:red;">Delivery times can be anywhere from 14-45 days on internationla orders.  We also follow the Royal Mail\'s guide on restricted goods.  If your order contains restricted goods, we will refund.  <a href="http://www.royalmail.com/business/help/sending/restricted-goods" target="_blank">Click here for details</a>.</td>
										</tr>
										
										
										<tr class="order-total">
											<th>Order Total</th>
											<td><strong><span class="amount">£'.number_format(($sub_total_post+$_POST["sub_total"]), 2, '.', '').'</span></strong>
											
											</td>
										</tr>	
	
	
	
	';
}

























function calc_int_post($s_id){

 
$sql = "SELECT 
				s_weight,
				s_height,
				s_length,
				s_width
			FROM
				spares
			WHERE
				spares.s_id = '".$s_id."'
	
	";
	$query		= mysql_query($sql);
	$row		= mysql_fetch_assoc($query);
	$num_rows	= mysql_num_rows($query);
	
	if ($num_rows>0){
		do{
			$weight 	= $row['s_weight'];
			$height 	= $row['s_height'];
			$length 	= $row['s_length'];
			$width 		= $row['s_width'];
		}while($row	= mysql_fetch_assoc($query));
	}
	
	
	if (
			($weight>0)
		&&
			($height>0)
		&&
			($length>0)
		&&
			($width>0)
	
	){
		
		$sql = "SELECT 
				*
			FROM
				post_prices
			WHERE
				pp_max_d > '".$height."'
			AND
				pp_max_l > '".$length."'
			AND
				pp_max_w > '".$width."'
			AND
				pp_weight > '".$weight."'
				
	
			";
			
			$query		= mysql_query($sql);
			$row		= mysql_fetch_assoc($query);
			$num_rows	= mysql_num_rows($query);
			$cheapest_uk = 0;
			$cheapest_EU = 0;
			$cheapest_R1 = 0;
			$cheapest_R2 = 0;
			
			if ($num_rows>0){
				do{
					/* cheapest UK */
					if (($cheapest_uk==0)&&($row['pp_location']=='UK')){
						$cheapest_uk = $row['pp_price'];
					}
					
					/* cheapest EU */
					if (($cheapest_EU==0)&&($row['pp_location']=='EU')){
						$cheapest_EU = $row['pp_price'];
					}
					
					/* cheapest R1 */
					if (($cheapest_R1==0)&&($row['pp_location']=='R1')){
						$cheapest_R1 = $row['pp_price'];
					}
					
					/* cheapest R2 */
					if (($cheapest_R2==0)&&($row['pp_location']=='R2')){
						$cheapest_R2 = $row['pp_price'];
					}
					
					
					
				}while($row	= mysql_fetch_assoc($query));
			}
			
			/*
				create the country dropdown
			*/
			$sql = "SELECT 
				*
			FROM
				countries
			WHERE
				c_id = '".$_POST['c_id']."'

			";
			
			$query		= mysql_query($sql);
			$row		= mysql_fetch_assoc($query);
			$num_rows	= mysql_num_rows($query);
			
			$country_dropdown = '<select style="max-widthInternational Prices:800px;  width: 500px; margin-top: 20px; font-size: 28px;" id="country_select">';
			$country_dropdown .= '<option> >></option>';
			if ($num_rows>0){
				$options = 0;
				do{
					
					/*
						work out price
					*/
					if ($row['c_id'] == '15'){ // UK
						$delivery_cost = '0.00';
						$selected = '';
					}elseif ($row['c_world_zone'] == '0'){ // EU
						$delivery_cost = $cheapest_EU;
						$selected = '';
					}elseif ($row['c_world_zone'] == '1'){ // r1
						$delivery_cost = $cheapest_R1;
						$selected = '';
					}elseif ($row['c_world_zone'] == '2'){ // r2
						$delivery_cost = $cheapest_R2;
						$selected = '';
					}
					return $delivery_cost;
					if ($delivery_cost>0){
					$country_dropdown .= '<option  value="'.$delivery_cost.'" '.$selected.'>'.$row['c_name'].' (&pound;'.$delivery_cost.')</option>';
					$options ++;
					}
					
				}while($row	= mysql_fetch_assoc($query));
			}
			$country_dropdown .='</select>';
			if ($options == 0){
				$country_dropdown = '';
			}
			#die($country_dropdown);
		
	}

}



?>