<?php session_start();?>
       <div class="row"> 
        <div class="col-lg-12">
          <div class="white-box">
            <h3 class="box-title m-b-0">Office Depot Products <?
				
				
				if 	($_SESSION['start_on']>0){
	echo '<div class="label label-table label-danger start_on" data-start="0" style="cursor:pointer;">Start on: 0</div>';
}else{
	echo '<div class="label label-table label-danger start_on" data-start="50" style="cursor:pointer;">Start on: 50</div>';
}	?></h3>
            <p class="text-muted m-b-20">Check and approve these products (remember to check whether it's being sold as a multiple on Amazon)</code></p>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th>Format</th>
                    <th>List Price</th>
                    <th>Qty</th>
                    <th>Min Price</th>
                    <th>Remove</th>
                    <th>List</th>
                  </tr>
                </thead>
                <tbody>
					<span id="od_counter" style="display:none;">0</span>
               <?php 


$hostname_tigers = "88.208.249.28";
$database_tigers = "contact25";
$username_tigers = "contact25-un";
$password_tigers = "mrW09n~8";
$tigers = mysql_pconnect($hostname_tigers, $username_tigers, $password_tigers) or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_select_db($database_tigers, $tigers) or die("Please refresh browser");

					
					
					
/* update prices to 1p less than amazon cheapest - for new products*/					
		$sql = 'select spares.s_id, spares.s_cheapest_amazon, spares.s_price from stock_c25, spares WHERE stock_c25.s_u_id = 22212 AND stock_c25.s_amazon_listed = 1
AND spares.s_id = stock_c25.s_s_id
AND spares.s_cheapest_amazon > (spares.s_price + 0.01)
						
			';#die($sql);
				
			$query = mysqli_query($db,$sql);
			$row = mysqli_fetch_assoc($query);
			$num_rows = mysqli_num_rows($query);

			if ($num_rows>0){
				do{
					$sql = 'UPDATE spares SET s_price = "'.($row['s_cheapest_amazon']-0.01).'" WHERE s_id = "'.$row['s_id'].'"';
					mysqli_query($db,$sql);
				}while($row = mysqli_fetch_assoc($query));
			}

					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
if ($_SESSION['u_id']!=1){
	die("no");
}
	#$_SESSION['limit'] = 1;				
if 	($_SESSION['start_on']>0){
	$limit = ($_SESSION['start_on']).",10"; 
}else{
	$limit = "10";
}			
					
					
					
$sql = 'SELECT 
			stock_c25.* 
		FROM 
			stock_c25, 
			stock_c25_updates 
		WHERE 
			stock_c25.s_u_id = 22212 
		AND 
			stock_c25.s_price_buy_delivery > 0 
		AND 
			stock_c25.s_price_buy < 10 
		AND 
			stock_c25.s_weight < 2000 
		AND 
			stock_c25.s_amazon_listed > -1 
		AND 
			stock_c25.s_amazon_listed < 1 
		AND 
			stock_c25.s_qty > 0
		AND
			stock_c25.s_s_id > 0
		AND
			(LENGTH(stock_c25.s_ISBN10) > 4)
		AND
			stock_c25_updates.s_SKU = stock_c25.s_SKU
		LIMIT '.$limit.'
						
			';#die($sql);
				
			$query = mysqli_query($db,$sql);
			$row = mysqli_fetch_assoc($query);
			$num_rows = mysqli_num_rows($query);

			if ($num_rows>0){
				do{
					$min_selling = $row['s_price_buy_inc_vat']; ## starting price
					
					if (($row['s_weight'] > 2000)&& ($row['s_price_buy_inc_vat']<25)){
						
						if($row['s_price_buy_inc_vat']<10){
							$min_selling = $min_selling + 6;
						}else{
							$min_selling = $min_selling + 5;
						}
					}else{
						$min_selling = $min_selling + 2;
					}
					## amazon fee
					$min_selling = number_format(($min_selling *1.18), 2);
					
					$list_price = $row['s_price'];
					if ($list_price<$min_selling){
						$list_price = floor($min_selling+10)+0.99;
					}
					
					$products .= '<tr id="hide_'.$row['s_id'].'">
                    <td><a href="https://www.amazon.co.uk/Mail-White-Bubble-Lined-260mm/dp/'.$row['s_ISBN10'].'/ref=sr_1_1?s=officeproduct&ie=UTF8&qid=1491827255&sr=1-1&keywords=5013719020042" target="_blank">'.$row['s_label'].'</a></td>
                    
                    <td><select name="format" class="format_'.$row['s_id'].'"><option value="P">PARCEL</option><option value="LL">LG LETTER</option></select> </td>
                    <td>£'.$list_price.'</td>
					<td>'.$row['s_qty'].'</td>
					<td>£'.$min_selling.'</td>
					<td><div class="label label-table label-danger remove_item" data-item="'.$row['s_id'].'" style="cursor:pointer;">Remove</div></td>
                    <td><div class="label label-table label-success list_item" data-item="'.$row['s_id'].'" data-s_s_id="'.$row['s_s_id'].'" data-min_selling="'.$min_selling.'" data-initial_list_price="'.$list_price.'" data-s_qty="'.$row['s_qty'].'" style="cursor:pointer;">List</div></td>
                    
                  </tr>
                  ';
					
				}while($row = mysqli_fetch_assoc($query));
			}


			
	echo $products;		





?> </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>