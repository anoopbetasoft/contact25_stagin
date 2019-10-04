<?php 

include_once("../../include/config.php");


$sql = 'SELECT 
			count(*) as "pending_orders"
		FROM 
			 order_details
		WHERE 
			order_details.od_rm_label = 0
		AND
			order_details.od_purchased_via = '.$_SESSION['c25_id'].'
		
		';

$query		= mysqli_query($db,$sql);
$row		= mysqli_fetch_assoc($query);
$pending_orders = $row['pending_orders'];

if ($pending_orders>99){
	$progress_orders = 100;
}else{
	$progress_orders = $pending_orders;
}


$sql = 'SELECT 
			sum(stock_c25.s_qty) as "items"
		FROM 
			 stock_c25
		WHERE 
			stock_c25.s_qty > 0
		AND
			stock_c25.s_u_id = '.$_SESSION['u_id'].'
		
		';

$query		= mysqli_query($db,$sql);
$row		= mysqli_fetch_assoc($query);
$items = $row['items'];

if ($items>99){
	$progress_items = 100;
}else{
	$progress_items = $items;
}

if ($pending_orders>99){
	$progress_orders = 100;
}else{
	$progress_orders = $pending_orders;
}


$sql = 'SELECT 
			sum(order_details.od_purchased_for) as "earnings"
		FROM 
			 order_details
		WHERE 
			order_details.od_rm_label = 1
		AND
			order_details.od_purchased_via = '.$_SESSION['u_id'].'
		
		';

$query		= mysqli_query($db,$sql);
$row		= mysqli_fetch_assoc($query);
$earnings = $row['earnings'];

if ($earnings>500){
	$progress_earnings = 100;
}else{
	$progress_earnings = floor($earnings/500*100);
}


?>
  
          





<div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
          <div class="white-box">
            <div class="row row-in">
              <div class="col-lg-3 col-sm-6 row-in-br">
                <div class="col-in row">
                  <div class="col-md-6 col-sm-6 col-xs-6"> <i class="linea-icon linea-basic fa-fw" data-icon="v" style="color:#03a9f3"></i>
                    <h5 class="text-muted vb">ORDERS</h5>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-6">
                    <h3 class="counter text-right m-t-15 text-info"><?php echo $pending_orders; ?></h3>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="progress">
                      <div class="progress-bar progress-bar-info wow animated progress-animated" role="progressbar" aria-valuenow="<?php echo $progress_orders; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress_orders; ?>%"> <span class="sr-only"><?php echo $progress_orders; ?>% Complete (success)</span> </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 row-in-br  b-r-none show_my_stuff" style="cursor:pointer;">
                <div class="col-in row">
                  <div class="col-md-6 col-sm-6 col-xs-6"> <i class="icon-folder" style="color:#fb9678"></i>
                    <h5 class="text-muted vb">MY STUFF</h5>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-6">
                    <h3 class="counter text-right m-t-15 text-danger"><?php echo $items?></h3>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="progress">
                      <div class="progress-bar progress-bar-danger wow animated progress-animated" role="progressbar" aria-valuenow="<?php echo $progress_items?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress_items?>%"> <span class="sr-only"><?php echo $progress_items?>% Complete (success)</span> </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 row-in-br">
                <div class="col-in row">
                  <div class="col-md-6 col-sm-6 col-xs-6"> <i class="ti-wallet text-success"></i>
                    <h5 class="text-muted vb">MONEY</h5>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-6">
                    <h3 class="counter text-right m-t-15 text-success" style="font-size:26px;">£<?php echo $earnings?></h3>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="progress">
                      <div class="progress-bar progress-bar-success wow animated progress-animated" role="progressbar" aria-valuenow="<?php echo $progress_earnings?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress_earnings?>%"> <span class="sr-only"><?php echo $progress_earnings?>% Complete (success)</span> </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6  b-0">
                <div class="col-in row">
                  <div class="col-md-6 col-sm-6 col-xs-6"> <i data-icon=")" class="linea-icon linea-basic fa-fw"  style="color:#fec107"></i>
                    <h5 class="text-muted vb">MESSAGES</h5>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-6">
                    <h3 class="counter text-right m-t-15 text-warning">157</h3>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="progress">
                      <div class="progress-bar progress-bar-warning wow animated progress-animated" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
