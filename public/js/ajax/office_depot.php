<?php 



include_once("../../include/config.php");

if ($_SESSION['u_id']!=1){
	die("no");
}
$sql = 'SELECT
						order_details.*, orders.*, stock_c25.*
						
					FROM
						orders, order_details
					LEFT JOIN 
						stock_c25
					ON
						order_details.od_s_id = stock_c25.s_s_id
					WHERE
						
					AND	
						order_details.od_purchased = 0
					AND
						(order_details.od_album = 7
						OR
						order_details.od_album = 17
						)
					AND
						order_details.od_ebay_item_ID > 0
					AND
						order_details.od_o_id = orders.o_id
					AND
						order_details.od_purchased_for > 0
					AND
						order_details.od_s_id > -1
					AND
						orders.o_paid = 1
					AND
						order_details.od_purchased_via = 22212	
					ORDER BY 
						stock_c25.s_price ASC
						
			';
				
			$query = mysqli_query($db,$sql);
			$row = mysqli_fetch_assoc($query);
			$num_rows = mysqli_num_rows($query);
			$filter_sql = '';
			$excel = 1;
			$counter = 0;
			$i = 1;
			if ($num_rows>0){
				do{
					if ($row['s_price_buy'] > 0){
						$buy_price = $row['s_price_buy'];
					}else{
						$buy_price = $row['s_price'];
					}
					
					$buy_total = $buy_total+($buy_price*$row['od_qty']);
					
					
					$repackaging_total_extra = $repackaging_total_extra + ($buy_price*$row['od_qty']);
					$counter ++;
					$i ++;
				}while($row = mysqli_fetch_assoc($query));
			}


			
			$products = '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="white-box" style="height:440px">
            <div class="product-img">
                <img src="http://payload170.cargocollective.com/1/7/253596/5700299/default_8280c791e2ee8551db03cb4e.jpg" height="200px;" style="margin: 30px"> 
                <div class="pro-img-overlay"><a href="javascript:void(0)" class="bg-info"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="bg-danger"><i class="ti-trash"></i></a></div>
            </div>
            <div class="product-text">
              <span class="pro-price bg-danger">£'.$buy_total.'</span>
              <h3 class="box-title m-b-0">Add more > £25 (free postage)</h3>
              
              
               <a target="_blank" href="http://contact25.com/classes/office_depot_orders.php?test=1"> <button class="btn btn-block btn-success btn-rounded"><i class="fa fa-tag m-l-5"></i> Order from OD</button></a>
             
			  
            </div>
          </div>
        </div>';
			
	


$sql = 'select * from orders, order_details WHERE orders.o_u_id = 1 AND order_details.od_o_id = orders.o_id AND order_details.od_purchased_via = 22212 group by order_details.od_s_id ORDER BY order_details.od_id ASC
			
	';
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	if ($num_rows>0){
		
		
	
		do{
			$title = strlen($row['od_num']) > 50 ? substr($row['od_num'],0,50)."..." : $in;
			$products .= '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="white-box" style="height:440px">
            <div class="product-img">
                <img src="http://contact25.com/uploads/7_'.$row['od_s_id'].'.jpg" height="200px;" style="margin: 30px"> 
                <div class="pro-img-overlay"><a href="javascript:void(0)" class="bg-info"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="bg-danger"><i class="ti-trash"></i></a></div>
            </div>
            <div class="product-text">
              <span class="pro-price bg-danger">£'.$row['od_purchased_for'].'</span>
              <h3 class="box-title m-b-0">'.$row['od_id'].'#'.$title.'</h3>
              <table width="200">
  <tbody>
    <tr>
      <td style="width:50%"><select style="width:100px" id="office_depot_'.$row['od_s_id'].'" data-office_depot_id="'.$row['od_s_id'].'">
	  	
			  	<option value = "0">Qty</option>
				<option value = "1">1</option>
				<option value = "2">2</option>
				<option value = "3">3</option>
				<option value = "4">4</option>
				<option value = "5">5</option>
			  </select></td>
			  
      <td> <button class="btn btn-block btn-success btn-rounded office_depot_add_order" data-office_depot_id="'.$row['od_s_id'].'"><i class="fa fa-tag m-l-5"></i> Add to Order</button>
              </td>
    </tr>
  </tbody>
</table>

			  
			  
            </div>
          </div>
        </div>';
			
		}while($row = mysqli_fetch_assoc($query));
		
		
		
	}else{
		return '';
	}


echo '<div style="clear:both;"></div>
      <div class="panel-heading">Add to Office Depot Order</div>
	  <!--row -->
     <div class="row">
        '.$products.'
        

        
      </div>';
die();


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




?>