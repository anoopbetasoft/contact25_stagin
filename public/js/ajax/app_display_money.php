<?php 

include("../../include/config.php");

global $db;

$sql = 'SELECT
			SUM(credits_debits.cd_value) AS "money_pending"
		FROM
			credits_debits
		WHERE
			credits_debits.cd_u_id = "'.$_SESSION['c25_id'].'"
		AND
			credits_debits.cd_o_id > 0
		AND
      		credits_debits.cd_timestamp > DATE_ADD(NOW(), INTERVAL -30 DAY)';

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	
	$pending_money = $row['money_pending'];


	$sql = '
			SELECT 
				currency.c_id, 
				currency.c_code, 
				currency.c_display, 
				users.u_currency
			FROM 
				currency, 
				users 
			WHERE 
				currency.c_id = users.u_currency 
			AND
				users.u_id =  "'.$_SESSION['c25_id'].'"
			';	

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	$currency_sign = $row['c_display'];


	$sql = '
			SELECT
				SUM(credits_debits.cd_value) AS "money_cleared",cd_bank_acc,cd_timestamp
			FROM
				credits_debits
			WHERE
				credits_debits.cd_u_id = "'.$_SESSION['c25_id'].'"
			AND
				credits_debits.cd_o_id > 0
			AND 
				(credits_debits.cd_timestamp < DATE_ADD(NOW(), INTERVAL -30 DAY)
			OR
				credits_debits.cd_value < 0)
			AND 
				credits_debits.cd_money_request_timestamp IS NULL
			';

	  
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	
	$money_cleared = $row['money_cleared'];


	$sql = '
			SELECT
				SUM(credits_debits.cd_value) AS "money_cleared",cd_bank_acc,cd_timestamp
			FROM
				credits_debits
			WHERE
				credits_debits.cd_u_id = 1
			AND
				credits_debits.cd_o_id > 0
			AND 
				(credits_debits.cd_timestamp < DATE_ADD(NOW(), INTERVAL -30 DAY)
			OR
				credits_debits.cd_value < 0)
			AND 
				credits_debits.cd_money_request_timestamp = "2019-01-08 06:51:28"
			';

	  
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	
		$money_cleared_up = $row['money_cleared'];
		$bank_acc = $row['cd_bank_acc'];
		$date = date("D, d F Y", strtotime($row['cd_money_request_timestamp']));

?>

                
               <!-- Column -->
                    <div class="col-lg-4 col-xlg-3 col-md-5">
                        <div class="card">
                            <div class="card-body">
								<br>
								<center>
									<h2>Summary</h2>
								</center>
                            </div>
                            <div>
                                <hr> </div>
							
							
							<div class="row text-center m-t-10">
								<div class="col-md-6 border-right">
									<h4 class="card-title">Pending:</h4>
									<p style="font-size:10px;">(available after 30 days)</p>
								</div>
								<div class="col-md-6">
									<br>
									<h4 class="card-title"><?=$currency_sign?><?=$pending_money?></h4>									
								</div>
							</div>
							
						
                                <hr>
							
							<div class="row text-center m-t-10">
								<div class="col-md-6 border-right">
									<h4 class="card-title">Available:</h4>
									<p style="font-size:10px;">(available to request)</p> 
								</div>
								<div class="col-md-6">
									<br>
									<h4 class="card-title"><?=$currency_sign?><?=$money_cleared?></h4>									
								</div>
							</div>
							<hr>
                            <center>						
								<div class="card-body">
									<button type="button" class="btn btn-success btn-rounded request_money_btn">Request Money</button>
								</div>
							</center>
                        </div>
                    </div>
					
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-8 col-xlg-9 col-md-7">
                        <div class="card">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs profile-tab" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab">Activity</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#settings" role="tab">Payment Details</a> </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="home" role="tabpanel">
                                    <div class="card-body">
                                        
                                            		 
                <div class="profiletimeline">
                                            <div class="sl-item">
                                                <div class="sl-left"> <img src="assets/images/no_user.jpg" alt="user" class="img-circle" /> </div>
                                                <div class="sl-right">
                                                    <div>
														Payment <?=$date?><br>
BACS Transfer £<?=$money_cleared_up?> to Acc: <?=$bank_acc?>  (<?=$date?>) 
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="sl-item">
                                                <div class="sl-left"> <img src="assets/images/no_user.jpg" alt="user" class="img-circle" /> </div>
                                                <div class="sl-right">
                                                    <div>
														Account Opened - Congratulations!
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 

                                    
                                    </div>
                                </div>
                                <!--second tab-->
                                
                                <div class="tab-pane" id="settings" role="tabpanel">
                                    <div class="card-body">
                                        <form class="form-horizontal form-material">
                  <div class="form-group">
                    <label class="col-md-12">Courier / Delivery Provider</label>
                    <div class="col-md-12">
                      <input type="text" placeholder="DHL / TNT / Yodel / Royal Mail" class="form-control form-control-line">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="example-email" class="col-md-12">Tracking URL</label>
                    <div class="col-md-12">
                      <input type="text" placeholder="https://courier-tracking-location" class="form-control form-control-line" name="example-email" id="example-email">
                    </div>
                  </div>
                  
					
                 
                  <div class="form-group">
                    <label class="col-sm-12">Inpost Next Day (UK ONLY)</label>
                    <div class="col-sm-12">
                      <select class="form-control form-control-line">
                        <option>Off</option>
                        <option>On</option>
                        
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12">
                      <button class="btn btn-success">Add</button>
                    </div>
                  </div>
                </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>         
                
           
