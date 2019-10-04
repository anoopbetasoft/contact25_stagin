<?php 

include("../../include/config.php");
global $db;
session_start();

/*
	The first part of these queries with var_search is old - kept in in case of modifying later - but essentially this page is just getting core orders - might search them later - search for a specific order for example

*/


	if (preg_match("/-/", $_POST['var_search'])) {
    	$check_amazon_or_order_id = '
				orders.o_amazon_order_id like "%'.$_POST['var_search'].'%"';
	} else {
		$check_amazon_or_order_id = '
				orders.o_id = "'.$_POST['var_search'].'"';
	}
	
if ($_POST['var_search'] == -1){
		$sql = 'SELECT 
			orders.o_id 
		FROM 
			orders, users, order_details
		WHERE 
			orders.o_is_dispatched < 1
		AND
			orders.o_paid = 1
		AND
			orders.o_u_id = users.u_id
	
		AND
			order_details.od_o_id = orders.o_id	
		AND
			order_details.od_purchased = 0
		AND
			order_details.od_purchased_via = 22212
		AND
			order_details.od_po = 0
		ORDER BY 
			order_details.od_album, 
			order_details.od_num,
			orders.o_delivery DESC, 
			order_details.od_qty
		';
	}elseif ($_POST['var_search'] == -2){
		$sql = ' 
			SELECT 
			orders.o_id 
		FROM 
			orders, order_details
		WHERE 
			orders.o_is_dispatched < 1
		AND
			orders.o_paid = 1
		AND
			order_details.od_o_id = orders.o_id	
		AND
			order_details.od_purchased = 1
		AND
			order_details.od_purchased_via = 22212
		AND
			order_details.od_rm_label = 0
		AND
			order_details.od_po > 0
		ORDER BY 
			order_details.od_album, 
			order_details.od_num,
			orders.o_delivery DESC, 
			order_details.od_qty
		';
	}else{	
$sql = '
		SELECT 
			orders.o_id 
		FROM 
			orders, order_details
		WHERE 
			orders.o_is_dispatched < 1
		AND
			orders.o_paid = 1
		AND
			order_details.od_o_id = orders.o_id	
		
		AND
			order_details.od_purchased_via = "'.$_SESSION['u_id'].'"
		AND
			order_details.od_rm_label = 0
	
		ORDER BY 
			order_details.od_album, 
			order_details.od_num,
			orders.o_delivery DESC, 
			order_details.od_qty	
			
		';}
	
$query		= mysqli_query($db,$sql);
$row		= mysqli_fetch_assoc($query);
$num_rows	= mysqli_num_rows($query);
if($num_rows == 0){
	
	$sql = 'SELECT 
			orders.o_id 
		FROM 
			orders, users, order_details
		WHERE 
			orders.o_is_dispatched < 1
		AND
			orders.o_paid = 1
		AND
			orders.o_u_id = users.u_id
	
		AND
			order_details.od_o_id = orders.o_id	
		AND
			order_details.od_purchased = 0
		AND
			order_details.od_purchased_via = 0
		ORDER BY 
			order_details.od_album, 
			order_details.od_num,
			orders.o_delivery DESC, 
			order_details.od_qty
		';
	
		
$query		= mysqli_query($db,$sql);
$row		= mysqli_fetch_assoc($query);
$num_rows	= mysqli_num_rows($query);
}
if ($num_rows >0){
	do{
		
		$result .= any_unweighted_items($row['o_id']);
		$result .= order_details($row['o_id']);
		
		
	}while($row	= mysqli_fetch_assoc($query));
}else{
	$result .= '
		<tr>
			<td colspan="2">
				<div style="padding:20px;  font-size:28px; background-color:white;color:black;text-align:center;">No Results</div>
			</td>
		</tr>
		';
}

function any_unweighted_items($o_id){
	global $db;
	$sql = 'SELECT 
			s_id,
			s_weight,
			s_format
		FROM 
			spares, order_details 
		WHERE 
			order_details.od_s_id = spares.s_id
		AND
			order_details.od_o_id = '.$o_id.'
		AND
		(
			(s_weight = 0)
			||
			(s_format IS NULL)
		)	
			
		';
		
	$query		= mysqli_query($db,$sql);
	$row		= mysqli_fetch_assoc($query);
	$num_rows	= mysqli_num_rows($query);
	if ($num_rows >0){
		$output = '';
		do{
			/*
			if ($row['s_weight'] == 0){
			$output .= '<tr>
			<td><input type="text" id="set_weight" data-s_id="'.$row['s_id'].'" placeholder="Set Weight" style="width:100%; border: 1px solid grey; font-size: 62px; text-align:center; "></td>
		</tr>';
			}
			
			if (!strlen($row['s_format'] >0)){
			$output .= '<tr>
			<td><input type="text" id="set_format" data-s_id="'.$row['s_id'].'" placeholder="Format (P/LL/L)" style="width:100%; border: 1px solid grey; font-size: 62px; text-align:center; "></td>
		</tr>';
			}
			*/
		}while($row	= mysqli_fetch_assoc($query));
	}
	
	return $output;
}

function order_details($o_id){
	global $db;
	$sql = 'SELECT 
			spares.*,
			order_details.*,
			stock_c25.*,
			spares.s_weight as "s_weight",
			spares.s_weight as "s_weight_actual"
		FROM 
			order_details, spares
		LEFT JOIN	 
			 stock_c25 
		ON
			spares.s_id = stock_c25.s_s_id
			
			
		WHERE 
			order_details.od_s_id = spares.s_id
	 
		AND
			order_details.od_po = '.$o_id.'
		AND
			order_details.od_rm_label = 0
		ORDER BY s_weight_actual, od_s_id, od_qty
			
		';
	
	$query		= mysqli_query($db,$sql);
	$row		= mysqli_fetch_assoc($query);
	$num_rows	= mysqli_num_rows($query);
	if ($num_rows == 0){
	$sql = 'SELECT 
			spares.*,
			order_details.*,
			stock_c25.*,
			spares.s_weight as "s_weight",
			spares.s_weight as "s_weight_actual"
		FROM 
			 order_details, spares
		LEFT JOIN	 
			 stock_c25 
		ON
			spares.s_id = stock_c25.s_s_id
		WHERE 
			order_details.od_s_id = spares.s_id
		
			
		AND
			order_details.od_o_id = '.$o_id.'
		AND
			order_details.s_u_id = '.$_SESSION['u_id'].'
		ORDER BY s_weight_actual, od_s_id, od_qty
			
		';
		#die($sql);
	$query		= mysqli_query($db,$sql);
	$row		= mysqli_fetch_assoc($query);
	$num_rows	= mysqli_num_rows($query);
	}#
	if ($num_rows == 0){
	$sql = 'SELECT 
			*
		FROM 
			spares, order_details 
		WHERE 
			order_details.od_s_id = spares.s_id
		AND
			order_details.od_o_id = '.$o_id.'
			
		ORDER BY s_weight, od_s_id, od_qty
			
		';
		
	$query		= mysqli_query($db,$sql);
	$row		= mysqli_fetch_assoc($query);
	$num_rows	= mysqli_num_rows($query);
	}
	
	
	if ($num_rows >0){
		
		$output = '';
		do{
			$more_than_one_product = morethanonecheck($row['od_o_id']);
			
			
			if ($more_than_one_product>1){
				$warning = "NOT SINGLE - CHECK WHAT THIS IS GOING WITH!!!";
			}else{
				$warning = "";
			}
			$delivery = 2;
			if ($row['s_format'] == 'LL'){
				$delivery = 0.6;
			}
			if ($row['s_format'] == 'L'){
				$delivery = 0.38;
			}
			$cost = $row['s_price_buy_inc_vat'];
			$amazon_fee = $row['od_price'] * .15;
			if ($amazon_fee<0.4){
				$amazon_fee=0.4;
			}
			$single_profit = $row['od_price']-$cost-$amazon_fee-$delivery;
			$profit = number_format(((($row['od_price']-$cost-$amazon_fee)*$row['od_qty'])-$delivery), 2, '.', '');
			
			if ($profit<0){
				$warning_col = "red";
				$profit = $profit.'!!! - minimum price should be &pound;'.(($profit*-1)+$row['od_price']).' <br>
<br>
SELECT * FROM spares WHERE s_id = "'.$row['s_s_id'].'"<br>
<br>
<a href="https://sellercentral.amazon.co.uk/inventory/ref=ag_invmgr_dnav_myo_?tbla_myitable=sort:%7B%22sortOrder%22%3A%22DESCENDING%22%2C%22sortedColumnId%22%3A%22date%22%7D;search:SKU-CONTACT25-'.$row['s_s_id'].';pagination:1;" target="_blank">Change on Amazon</a>
';
			}else{
				$warning_col = "green";
			}
			
			
			if ($row['od_returned']==1){
				$retuned = '<span style="color:red"><strong>RETURNED</strong> - address not recognised</span><br>
';
			}elseif ($row['od_returned']==2){
				
				$retuned .= '<span style="color:red"><strong>RETURNED</strong> - not collected from local depot</span><br>
';
			}else{
				$retuned = "";
			}
			
			
			if ($row['od_rm_label']==1){
				$label = '<a href="/rm_labels/'.$row['od_o_id'].'.pdf" target="_blank">Print Label (pdf)</a><br>
				
';
			}
			
			
			if ($row['stock_weight']>0){
				$weight = $row['stock_weight']*$row['od_qty'];
			}else{
				$weight = $row['s_weight']*$row['od_qty'];
			}
			
			$format=$row['s_format'];
			if ($weight>750){
				$format='P';
			}
			if($weight>2000){
				$format='P24';
			}
			if ($row['od_sent_direct'] == 1){
				$format='SENT FROM SUPPLIER';
			}
			
			if ($weight>0){
				$display_weight = '('.($weight).'g) ';
			}else{
				$display_weight = '';
			}
			
			if (strlen($row['od_collection'])>0){
				$collection = '<span style="color:green;font-weight:strong;">Collecting:<br>
</span> '.display_collection_date($row['od_collection']).''.$warning.'
			   
			   <div style="padding:5px"></div>

			   
			   
			   <div style="padding:15px"></div>
			   
			   
			   <a href="#hash_link_'.$row['od_o_id'].'" class="btn btn-custom btn-block waves-effect waves-light produce_rm_label hide_'.$row['od_o_id'].'" data-order_id='.$row['od_o_id'].' style="background-color: #03a9f3; border:#03a9f3; height: 150px;font-size:30px;"><br>
COLLECTED</a>
			   ';
				
			}else{
				
				// only show packing on non-collection orders //
				$collection = ' <h3 class="box-title m-b-0" style="font-size:30px;">'.$format.' <span style="font-size:18px">'.($display_weight).' '.$row['s_ISBN13'].'</span></h3>'.$warning.'
			   
			   <div style="padding:5px"></div>

			   
			   
			   <div style="padding:15px"></div>
			   
			   
			   <a href="#hash_link_'.$row['od_o_id'].'" class="btn btn-custom btn-block waves-effect waves-light produce_rm_label hide_'.$row['od_o_id'].'" data-order_id='.$row['od_o_id'].' style="background-color: #03a9f3; border:#03a9f3; height: 150px;font-size:30px;"><br>
PRINT LABEL</a>
			   ';
			}
			
			
			$output .= '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hash_link_'.$row['od_o_id'].'">
          <div class="white-box">
            <div class="product-img">
                <img src="http://contact25.com/uploads/7_'.$row['od_s_id'].'.jpg" height="200px;" style="margin: 30px"> 
                <div class="pro-img-overlay"><a href="javascript:void(0)" class="bg-info"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="bg-danger"><i class="ti-trash"></i></a></div>
            </div>
            <div class="product-text">
			
			
              <span class="pro-price bg-danger">'.$row['od_qty'].'</span>
			  <h1><span style="color:'.$warning_col.'; ">&pound;'.$profit.'</span></h1>
              <h3 class="box-title m-b-0">'.$row['od_num'].'</h3>
             <br>'.$retuned.$label.$collection.'
			  
			   
			
			   
			   
			  
			   
           
          
        
		
</div></div></div>
			
			
			';
			// dev version of label - for RM API
			// 
			
			
		}while($row	= mysqli_fetch_assoc($query));
		
		
		
	}
	
	//$output .= ' <div style="clear:both;"></div>';
		echo $output;
}

function display_collection_date($collection_date){
	
	$output = '';
	
	if (date("Y-m-d", strtotime($collection_date)) == date("Y-m-d", strtotime('now'))){
		// today
		$output .= 'TODAY';
	}else{
		$output .= date("D, j M H:i", strtotime($collection_date));
	}
	
	if (date("Y-m-d", strtotime($collection_date)) < date("Y-m-d", strtotime('now'))){
		// today
		$output .= 'Already Collected';
	}else{
		$date1=date_create($collection_date);
		$date2=date_create(date("Y-m-d H:i:s"));
		$diff=date_diff($date1,$date2);
		//echo $diff->format("%R%a days");
		$coundown = '';
		
		if ($diff->i>0){
			$coundown .= '<span style="color:red">';
		}
		
		if ($diff->d>0){
			if ($diff->d == 1){
				$plural = '';
			}else{
				$plural = 's';
			}
			$coundown .= $diff->d.' day'.$plural.', ';
		}
		if ($diff->h>0){
			if ($diff->h == 1){
				$plural = '';
			}else{
				$plural = 's';
			}
			$coundown .= $diff->h.'hr'.$plural.', ';
		}
		if ($diff->i>0){
			if ($diff->i == 1){
				$plural = '';
			}else{
				$plural = 's';
			}
			$coundown .= $diff->i.'min'.$plural.'';
		}
		if ($diff->i>0){
			$coundown .= '</span>';
		}
		$output .= '<br>
'.$coundown;
	}
	
	return $output;
}



echo '<div class="row">'.$result.'
        

        
      </div>';
die();
/* login*/

	#$barcode = 9780263255683; ## test barcode when there is nothing to scan - best selling diet book ##
	$barcode = $_POST['barcode'];
	$barcode = '5051561040740';
	#$barcode = '5051429101842';
	$result = barcode_lookup_add($barcode);
	echo json_encode(array($result[0], $result[1]));


function barcode_lookup_add($barcode){
	
	$sql = 'SELECT * FROM spares WHERE spares.s_ISBN13 = "'.$barcode.'" LIMIT 1';
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	$basket_list = '';
	$output = '';
	$i=1;
do{
	$options .= '<option>'.$i.'</option>';
	$i++;
}while($i<51);

	if ($num_rows>0){
		do{
			$output .=  ' <div style="font-size: 58px; text-align: center; padding-bottom:10px; margin-left:20%; margin-right:20%"><select class="form-control s_qty">
                    '.$options.'
                  </select></div>';
			$output .=  '<div style="text-align:center;font-size: 18px;">'.$row['s_label'].'</div>';
			$output .= "<br>";
			$output .= '<img src="http://contact25.com/uploads/7_'.$row['s_id'].'.jpg" alt="'.$row['s_label'].'" style="width:100%">
			
			';
			$prod_info = array($row['s_id'], $row['s_ISBN10'], $row['s_ISBN13'], $row['s_weight'], $row['s_height'], $row['s_length'], $row['s_width'], $row['s_label'], $row['s_price'], $row['s_price_like_new'], $row['s_price_good'], $row['s_price_ok']);
			return array($output, $prod_info);
		}while($row = mysqli_fetch_assoc($query));
	}else{
		
		include('/home/vhosts/contact25.com/httpdocs/classes/class.amazon.php');	
		include('/home/vhosts/contact25.com/httpdocs/config.php');
		$amazon 				= new amazon();
		$amazon->amazon_aws_definitions_uk();
		$serviceUrl 			= "mws-eu.amazonservices.com";
		$amazon->amazon_aws_barcode_search($barcode, $serviceUrl);
		$output = barcode_lookup_add($barcode);
		
	}
}




function morethanonecheck($o_id){
	$sql = 'SELECT 
			count(*) AS counter
		FROM 
			order_details 
		WHERE 
			order_details.od_o_id = '.$o_id.'
		';
		
	$query		= mysqli_query($db,$sql);
	$row		= mysqli_fetch_assoc($query);
	$num_rows	= mysqli_num_rows($query);
	return $row['counter'];
	
	
}


?>