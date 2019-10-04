<?php 



$hostname_tigers = "88.208.249.28";
$database_tigers = "contact25";
$username_tigers = "contact25-un";
$password_tigers = "mrW09n~8";
$tigers = mysql_pconnect($hostname_tigers, $username_tigers, $password_tigers) or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_select_db($database_tigers, $tigers) or die("Please refresh browser");

session_start();

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
$sql = 'SELECT 
			orders.o_id  
		FROM 
			orders
		WHERE 
			 
			(
				
				'.$check_amazon_or_order_id.'
			)
			
			
		';}

$query		= mysql_query($sql);
$row		= mysql_fetch_assoc($query);
$num_rows	= mysql_num_rows($query);
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
	
	
$query		= mysql_query($sql);
$row		= mysql_fetch_assoc($query);
$num_rows	= mysql_num_rows($query);
}
if ($num_rows >0){
	do{
		
		$result .= any_unweighted_items($row['o_id']);
		$result .= order_details($row['o_id']);
		
		
	}while($row	= mysql_fetch_assoc($query));
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
		
	$query		= mysql_query($sql);
	$row		= mysql_fetch_assoc($query);
	$num_rows	= mysql_num_rows($query);
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
		}while($row	= mysql_fetch_assoc($query));
	}
	
	return $output;
}

function order_details($o_id){
	
	$sql = 'SELECT 
			spares.*,
			order_details.*,
			stock_c25.*,
			spares.s_weight as "s_weight",
			spares.s_weight as "s_weight_actual",
			orders.o_dispatched
		FROM 
			order_details, orders, spares
		LEFT JOIN	 
			 stock_c25 
		ON
			spares.s_id = stock_c25.s_s_id
			
			
		WHERE 
			order_details.od_s_id = spares.s_id
	 	AND
			orders.o_id = order_details.od_o_id
		AND
			order_details.od_po = '.$o_id.'
		AND
			order_details.od_rm_label = 0
		group by 
			od_s_id, od_o_id
		ORDER BY s_weight_actual, od_s_id, od_qty
			
		';
	
	$query		= mysql_query($sql);
	$row		= mysql_fetch_assoc($query);
	$num_rows	= mysql_num_rows($query);
	if ($num_rows == 0){
	$sql = 'SELECT 
			spares.*,
			order_details.*,
			stock_c25.*,
			spares.s_weight as "s_weight",
			spares.s_weight as "s_weight_actual",
			orders.o_dispatched
		FROM 
			 order_details, orders, spares
		LEFT JOIN	 
			 stock_c25 
		ON
			spares.s_id = stock_c25.s_s_id
		WHERE 
			order_details.od_s_id = spares.s_id
		AND
			orders.o_id = order_details.od_o_id
			
		AND
			order_details.od_o_id = '.$o_id.'
		ORDER BY s_weight_actual, od_s_id, od_qty
			
		';
		
	$query		= mysql_query($sql);
	$row		= mysql_fetch_assoc($query);
	$num_rows	= mysql_num_rows($query);
	}#
	if ($num_rows == 0){
	$sql = 'SELECT 
			spares.*,
			order_details.*,
			orders.o_dispatched,
			stock_c25.s_SKU
		FROM 
			orders, spares, order_details 
		LEFT JOIN
			stock_c25
		ON
			(
				stock_c25.s_s_id = order_details.od_s_id
			AND
				order_details.od_purchased_via = stock_c25.s_u_id
			)
		WHERE 
			order_details.od_s_id = spares.s_id
		AND
			order_details.od_o_id = '.$o_id.'
		AND
			orders.o_id = order_details.od_o_id
		ORDER BY s_weight, od_s_id, od_qty
			
		';
		
	$query		= mysql_query($sql);
	$row		= mysql_fetch_assoc($query);
	$num_rows	= mysql_num_rows($query);
	}
	
	
	if ($num_rows >0){
		$output = '';
		do{
			$more_than_one_product = morethanonecheck($row['od_o_id']);
			if ($more_than_one_product>1){
				$warning = "PART OF LARGER ORDER WITH MULTIPLE ITEMS!!!";
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
				$label = '<a href="/rm_labels/'.$row['od_o_id'].'.pdf" target="_blank" style="font-size: 42px; color:red;"> <i class="fa fa-file-pdf-o"></i></a>
				
';
			}else{
				$label = '<a href="/rm_labels/'.$row['od_o_id'].'.pdf" class="print_label_again_'.$row['od_o_id'].'" target="_blank" style="display:none;font-size: 42px;color:red;"><i class="fa fa-file-pdf-o"></i></a>
				
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
				$format='SENT FROM SUPPLIER <br><span style="color:green; font-size:14px">
ORDER DATE: '.date("d M Y", strtotime($row['o_dispatched'])).'</span><br>
';
			}
			
			$format = '<span style="font-size:48px; color:#03a9f3;">'.$format.'</span>';
			
			/*add passing for warning*/
			if (strlen($warning)>0){
				$warning = '<div style="padding:0px 15px 15px 15px; font-weight:bold; font-size:16px; color:red;">'.$warning.'</div>';
			}
			
			   if (strlen($row['s_SKU'])>0){
				   
				   $SKU = '<span style="font-size: 12px">(SKU: '.$row['s_SKU'].')</span>';
				   
			   }else{
				  $SKU = $row['s_SKU']; 
			   }
			
			if (strlen($row['s_format'])>0){
				$format_display = $row['s_format'];
			}else{
				$format_display = 0;
			}
			
			if ($format_display == 0){
				$options = '<option selected>>></option>';
			}else{
				$options = '<option></option>';
			}
			
			if ($format_display == 'L'){
				$options .= '<option selected>L</option>';
			}else{
				$options .= '<option>L</option>';
			}
			if ($format_display == 'LL'){
				$options .= '<option selected>LL</option>';
			}else{
				$options .= '<option>LL</option>';
			}
			if ($format_display == 'P'){
				$options .= '<option selected>P</option>';
			}else{
				$options .= '<option>P</option>';
			}
			
			$update_weight ='
				
			   <select class="update_format" data-s_id='.$row['od_s_id'].'>
			   		'.$options.'
			   </select>';
			
			
			
			if (strlen($row['s_packing'])>0){
				$format_display = $row['s_packing'];
			}else{
				$format_display = 0;
			}
			
			if ($format_display == 0){
				$options = '<option selected>>></option>';
			}else{
				$options = '<option>>></option>';
			} 
			
			if ($format_display == '1'){
				$options .= '<option value=1 selected >A/000</option>';
			}else{
				$options .= '<option value=1>A/000</option>';
			}
			if ($format_display == '2'){
				$options .= '<option value=2 selected>B/00</option>';
			}else{
				$options .= '<option value=2>B/00</option>';
			}
			if ($format_display == '3'){
				$options .= '<option value=3 selected>C/0</option>';
			}else{
				$options .= '<option value=3>C/0</option>';
			}
			if ($format_display == '4'){
				$options .= '<option value=4 selected>D/1</option>';
			}else{
				$options .= '<option value=4>D/1</option>';
			}
			if ($format_display == '5'){
				$options .= '<option value=5 selected>E/2</option>';
			}else{
				$options .= '<option value=5>E/2</option>';
			}
			if ($format_display == '6'){
				$options .= '<option value=6 selected>F/3</option>';
			}else{
				$options .= '<option value=6>F/3</option>';
			}
			if ($format_display == '7'){
				$options .= '<option value=7 selected>G/4</option>';
			}else{
				$options .= '<option value=7>G/4</option>';
			}
			if ($format_display == '8'){
				$options .= '<option value=8 selected>H/5</option>';
			}else{
				$options .= '<option value=8>H/5</option>';
			}
			if ($format_display == '9'){
				$options .= '<option value=9 selected>J/6</option>';
			}else{
				$options .= '<option value=9>J/6</option>';
			}
			if ($format_display == '10'){
				$options .= '<option value=10 selected>K/7</option>';
			}else{
				$options .= '<option value=10>K/7</option>';
			}
			if ($format_display == '11'){
				$options .= '<option value=11 selected>BOX</option>';
			}else{
				$options .= '<option value=11>BOX</option>';
			}
			
			$update_packing ='
				
			   <select class="update_packing" data-s_id='.$row['od_s_id'].'>
			   		'.$options.'
			   </select>';
			
			if ($row['s_packing'] == 0){
	$packed_display = '...';
}

if ($row['s_packing'] == 1){
	$packed_display = 'A/000'; 	
}
if ($row['s_packing'] == 2){
	$packed_display = 'B/00'; 	
}
if ($row['s_packing'] == 3){
	$packed_display = 'C/0'; 	
}
if ($row['s_packing'] == 4){
	$packed_display = 'D/1'; 	
}
if ($row['s_packing'] == 5){
	$packed_display = 'E/2'; 	
}
if ($row['s_packing'] == 6){
	$packed_display = 'F/3'; 	
}
if ($row['s_packing'] == 7){
	$packed_display = 'G/4'; 	
}
if ($row['s_packing'] == 8){
	$packed_display = 'H/5'; 	
}
if ($row['s_packing'] == 9){
	$packed_display = 'J/6'; 	
}
if ($row['s_packing'] == 10){
	$packed_display = 'K/7'; 	
}
if ($row['s_packing'] == 11){
	$packed_display = 'BOX'; 	
}
			
			
			if (strlen($row['s_next_format'])>0){
				$nextformat_display = $row['s_next_format'];
			}else{
				$nextformat_display = 0;
			}
			
			if ($nextformat_display == 0){
				$options = '<option selected value="">NEXT FORMAT</option>';
			}else{
				$options = '<option value="">NEXT FORMAT</option>';
			}
			
			if ($nextformat_display == '1'){
				$options .= '<option selected>1</option>';
			}else{
				$options .= '<option>1</option>';
			}
			if ($nextformat_display == '2'){
				$options .= '<option selected>2</option>';
			}else{
				$options .= '<option>2</option>';
			}
			if ($nextformat_display == '3'){
				$options .= '<option selected>3</option>';
			}else{
				$options .= '<option>3</option>';
			}
			if ($nextformat_display == '4'){
				$options .= '<option selected>4</option>';
			}else{
				$options .= '<option>4</option>';
			}
			if ($nextformat_display == '5'){
				$options .= '<option selected>5</option>';
			}else{
				$options .= '<option>5</option>';
			}
			
			if ($nextformat_display==0){
				$nextformat_display = '';
			}
			
			$update_nextformat ='
				
			   <select class="update_nextformat" data-s_id='.$row['od_s_id'].'>
			   		'.$options.'
			   </select>';
			
			$output .= '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hash_link_'.$row['od_o_id'].'">
          <div class="white-box">
            <div class="product-img">
                <img src="http://contact25.com/uploads/7_'.$row['od_s_id'].'.jpg" height="200px;" style="margin: 30px"> 
                <div class="pro-img-overlay"><a href="javascript:void(0)" class="bg-info"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="bg-danger"><i class="ti-trash"></i></a></div>
            </div>
            <div class="product-text">
              <span class="pro-price bg-danger">'.$row['od_qty'].'</span>
			  <h1>'.$row['od_o_id'].' '.$SKU.'</h1>
              <h3 class="box-title m-b-0"><a href="https://sellercentral.amazon.co.uk/inventory/ref=ag_invmgr_dnav_myo_?tbla_myitable=sort:%7B%22sortOrder%22%3A%22DESCENDING%22%2C%22sortedColumnId%22%3A%22date%22%7D;search:SKU-CONTACT25-'.$row['s_s_id'].';pagination:1;" target="_blank">'.$row['od_num'].'</h3>
              <small class="text-muted db">&pound;'.$row['od_price'].' (<span style="color:'.$warning_col.'">&pound;'.$profit.')</span> '.$row['s_ISBN13'].' ('.$row['s_id'].')</small><br>'.$retuned.'
			   <h3 class="box-title m-b-0" style="font-size:30px;">'.$label.'<span class="format_display format_display_'.$row['od_s_id'].'" style="color:green; font-size:68px;">'.$format.'</span><span class="nextformat_display" font-size:10px;>'.$nextformat_display.'</span></span> <span class="packing_display packing_display_'.$row['od_s_id'].'" style="color:orange;">'.$packed_display.'</span> <span style="font-size:18px">(<span class="weight_display weight_display_'.$row['od_s_id'].'">'.($weight).'</span>g)</span></h3><br>
			   
			   
			   <div class="spinner_update_size_weight"></div>
				<span style="font-size:30px;">
				'.$update_weight.'
				
			  	'.$update_packing.'


			   <input type="text" placeholder="Weight" class="weight_update" data-s_id='.$row['od_s_id'].' value="'.$row['s_weight'].'" style="width: 80px">
			   
			   
			   '.$update_nextformat.'
			    
			   </span>
			   
			   <div style="padding:15px"></div>
			   
			   <a target="_blank" href="http://contact25.com/tcpdf/examples/invoice.php?special_req='.$row['od_o_id'].'" class="btn btn-custom  btn-block waves-effect waves-light text-info" style="background-color: #00c292; border:#00c292;" >INV COPY PDF</a>
			  
				   <a href="#hash_link_'.$row['od_o_id'].'" class="btn refund_display_wrapper_'.$row['od_o_id'].' btn-custom  btn-block waves-effect waves-light text-info damaged_req_refund_reorder" data-order_id='.$row['od_o_id'].' data-s_id='.$row['od_s_id'].' style="background-color: #03a9f3; border:#03a9f3;height: 50px;" >DAMAGED<br>
	RE-ORDER & REQ REFUND</a>
			   
			   
			   
				<a  href="#hash_link_'.$row['od_o_id'].'" data-order_id='.$row['od_o_id'].' data-s_id='.$row['od_s_id'].' class="btn btn-custom  btn-block waves-effect waves-light text-info undelivered_chase_supplier undelivered_wrapper_'.$row['od_o_id'].'" style="background-color: #00c292; border:#00c292;""  >UNDELIVERED - CHASE SUPPLIER</a>
				
				<div class="refund_'.$row['od_o_id'].'" style="margin-bottom:5px;"></div>
				
			   <div style="padding:15px"></div>

				
			   
			   
			   <div style="padding:15px"></div>
			   '.$warning.'
			   
			    <a href="#hash_link_'.$row['od_o_id'].'" class="btn btn-custom btn-block waves-effect waves-light produce_rm_label_upgrade hide_'.$row['od_o_id'].'" data-order_id='.$row['od_o_id'].' style="background-color: red; border:#03a9f3; font-size:30px;">
RM24</a>
			   
			   
			   <a href="#hash_link_'.$row['od_o_id'].'" class="btn btn-custom btn-block waves-effect waves-light produce_rm_label hide_'.$row['od_o_id'].'" data-order_id='.$row['od_o_id'].' style="background-color: #03a9f3; border:#03a9f3; height: 150px;font-size:30px;"><br>
API LABEL <i class="ti-angle-double-right"></i></a>
			   
			  
			   
            </div>
          </div>
        </div>
		

			
			
			';
			// dev version of label - for RM API
			// 
			
			
		}while($row	= mysql_fetch_assoc($query));
		
		$output .= '<div style="clear:both;"></div>';
		
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
	$query = mysql_query($sql);
	$row = mysql_fetch_assoc($query);
	$num_rows = mysql_num_rows($query);
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
		}while($row = mysql_fetch_assoc($query));
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
		
	$query		= mysql_query($sql);
	$row		= mysql_fetch_assoc($query);
	$num_rows	= mysql_num_rows($query);
	return $row['counter'];
	
	
}


?>