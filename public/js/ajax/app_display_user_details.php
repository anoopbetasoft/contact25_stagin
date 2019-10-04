<?php 

include("../../include/config.php");

global $db;

$sql = 'SELECT 
			COUNT(users_friends.uf_u_id) AS "user_count"
		FROM  
			users_friends
		WHERE 
			users_friends.uf_u_id =  "'.$_SESSION['c25_id'].'" 
		';

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	$friends = $row["user_count"];


$sql = 'SELECT 
			count(stock_c25.s_room) AS "box_count" 
		FROM
				stock_c25 
		WHERE 
				stock_c25.s_u_id =  "'.$_SESSION['c25_id'].'"
		';

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	$box_friend = $row["box_count"];

$sql = 'SELECT 
			users.u_name, users.u_amazon_supplier_name  
		FROM
			users
		WHERE 
			users.u_id =  "'.$_SESSION['c25_id'].'"
		';

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);


	$user_name = $row["u_name"];
	$company_name = $row["u_amazon_supplier_name"];


$sql = 'SELECT 
			count(order_details.od_purchased_via) AS "sales_count"  
		FROM
			order_details
		WHERE 
			order_details.od_purchased_via =  "'.$_SESSION['c25_id'].'"
		';

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	$number_of_sales = $row["sales_count"];

	
			do{
				#Add an || for postcode and second address# #change code to show address or not depending if there is user information#
				if (1>0){
						$user_detail_display = '<center class="m-t-30"> 
						<i class="far fa-meh" style="font-size:128px; color:#03a9f3;"></i>
						<!--<img src="assets/images/logo-balls-profile-temp-image.png" class="img-circle" width="120px"/>-->
						
						
									<div class="row text-center justify-content-md-center">
                                        <div class="col-4"><a href="javascript:void(0)" class="link"><i class="fa fa-upload"></i> <font class="font-medium"></font></a></div>

                                        <div class="col-4"><a href="javascript:void(0)" class="link"><i class="fa fa-camera"></i> <font class="font-medium"></font></a></div>
                                    </div>
                                    <h4 class="card-title m-t-10">'.$user_name.'</h4>
                                    <h6 class="card-subtitle">'.$company_name.'</h6>
                                    <div class="row text-center justify-content-md-center">
                                        <div class="col-4"><a href="javascript:void(0)" class="link"><i class="icon-people"></i> <font class="font-medium">'.$friends.'</font></a></div>
										<div class="col-4"><a href="javascript:void(0)" class="link"><i class="ti-bar-chart"></i> <font class="font-medium">'.$number_of_sales.'</font></a></div>
                                        <div class="col-4"><a href="javascript:void(0)" class="link"><i class="ti-package"></i> <font class="font-medium">'.$box_friend.'</font></a></div>
                                    </div>
                                </center>
					';
					}else{
						$user_detail_display = '';
					}
					
				
				print $user_detail_display

								
				;
				 
			}while($row = mysqli_fetch_assoc($query));


	



?>
