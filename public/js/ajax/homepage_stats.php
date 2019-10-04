<?php 

if (strlen($_SESSION['u_name'])>0){
	
	// already logged in
	$_SESSION['u_name'] 					= $u_name;
	$_SESSION['earnings_last_30_days'] 		= $earnings_last_30_days;
	$_SESSION['dropdown'] 					= $dropdown;
	$_SESSION['messages_dropdown'] 			= $messages_dropdown;
	$_SESSION['top_menu'] 					= $top_menu;
	$_SESSION['settings_sidebar'] 			= $settings_sidebar;
	$_SESSION['preferences'] 				= $preferences;
	$_SESSION['logout'] 					= $logout;

	echo json_encode(array($_SESSION['u_name'], $_SESSION['earnings_last_30_days'], $_SESSION['dropdown'], $_SESSION['messages_dropdown'],$_SESSION['top_menu'],$_SESSION['settings_sidebar'],$_SESSION['preferences'], $_SESSION['logout']));
	die();
}


include("../../include/config.php");
include_once("../../include/class.datetime.php");
$datetime = new datetimeformat();

/* login*/

if ($_SESSION['c25_id'] == 0){
	// it's forgotton it's session variable //
	$logout = -1;
	// recover userID from local storage - app only //
	if (strlen($_POST['c25_id'])>0){
		$enc = str_replace(' ', '+', $_POST['c25_id']);
		$sql = 'SELECT
					u_id
				FROM 
					users 
				WHERE 
					u_pass_enc = "'.$enc.'" 
				LIMIT 1';

		$query = mysqli_query($db,$sql);
		$row = mysqli_fetch_assoc($query);
		$num_rows = mysqli_num_rows($query);
		
		if ($num_rows > 0){
			$_SESSION['c25_id'] = $row['u_id'];
			$logout = $enc;
		}
	}
	
	
}else{
	
	if (strlen($_POST['c25_id'])>0){
		$enc = str_replace(' ', '+', $_POST['c25_id']);
		$logout = $enc;
	}
	
	
	
	$logout = 1;
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// don't forget when saving first time to check for any other rnadom 200 string passwords on the users table!! //
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	

	// if it's on the app, save it in local storage //
	if (($_SERVER['SERVER_NAME'] == 'newapp.contact25.com')||($_SERVER['SERVER_NAME'] == 'contact25.com')){
		$logout = -1;
		$bytes = openssl_random_pseudo_bytes(200);
		$hash = base64_encode($bytes);
		$sql = 'UPDATE 
				 	users 
			SET
				u_pass_enc = "'.$hash.'"
			WHERE
				u_id = "'.$_SESSION['c25_id'].'" 
			';

		$query = mysqli_query($db,$sql);
		$logout = addslashes($hash);
	}else{
		$hash = bin2hex(random_bytes(16));
		$saved_key =  serialize($hash);
		$sql = 'UPDATE 
				 	users 
			SET
				u_pass_enc = "'.$saved_key.'"
			WHERE
				u_id = "'.$_SESSION['c25_id'].'" 
			';

		$query = mysqli_query($db,$sql);
		$logout = $saved_key;
	}
}

	$sql = 'SELECT
				u_name,
				u_sell_f,
				u_sell_n,
				u_sell_c,
				u_lend_f,
				u_lend_n,
				u_lend_c,
				u_pass_enc
			FROM 
				users 
			WHERE 
				u_id = "'.$_SESSION['c25_id'].'" 
			LIMIT 1';

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	$u_name = '<a href="pages-register.html>Register</a>';
	if ($num_rows>0){
		do{
			$u_name = $row['u_name'];
			$preferences = array(
							$row['u_sell_f'],
							$row['u_sell_ff'],	
							$row['u_sell_n'],	
							$row['u_sell_c'],	
							$row['u_lend_f'],	
							$row['u_lend_ff'],	
							$row['u_lend_n'],	
							$row['u_lend_c']
			);
			//$logout = addslashes($row['u_pass_enc']);
		}while($row = mysqli_fetch_assoc($query));
	}else{
		//json_encode(array($u_name, $earnings_last_30_days, $dropdown, $messages_dropdown,$top_menu,$settings_sidebar,$preferences, -1));
	}


	if ($_SESSION['c25_id'] == 1){
		$extra_sql = ' OR order_details.od_purchased_via = 0';
	}
	if ($_SESSION['c25_id'] != 1){
		$purchased_via_filter = 'order_details.od_purchased_via = '.$_SESSION['c25_id'].'';
	}else{
		$purchased_via_filter = ('order_details.od_purchased_via >-1');
	}


	$sql = 'select sum(order_details.od_price) as "sales_total" from order_details, orders WHERE ('.$purchased_via_filter.' '.$extra_sql.')
AND order_details.od_o_id = orders.o_id
AND orders.o_dispatched >= (CURDATE() - INTERVAL 1 MONTH )';
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	$earnings_last_30_days = '£0';
	if ($num_rows>0){
		do{
			if ($row['sales_total']>0){
					$earnings_last_30_days = '<strong>£'.number_format($row['sales_total'],2).'</strong>';
			}else{
					$earnings_last_30_days = '£0';
			}
		}while($row = mysqli_fetch_assoc($query));
	}

$sql = 'SELECT
			*
		FROM
			order_details
		WHERE
			order_details.od_purchased_via = '.$_SESSION['c25_id'].'
		AND
			order_details.od_purchased = 0
		AND
			order_details.od_rm_label = 0

		order by od_id DESC
		LIMIT 10';
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	
	if ($num_rows>0){
		$new_sales_dropdown = '<!-- ============================================================== -->
                        <!-- Sales -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="ti-bar-chart"></i>
                                <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                            </a>
                            <div class="dropdown-menu mailbox dropdown-menu-right animated bounceInDown" aria-labelledby="2">
                                <ul>
                                    <li>
                                        <div class="drop-title">You have '.$num_rows.' new sales</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                       	
                                        ';
		do{
			
			if (!$row['od_collection'] == NULL){
				$collect_deliver_by = '<span class="text-success"><i class="fa fa-map-marker"></i>  '.$datetime->time_coming_up($row['od_collection']).'</span>';
				$countdown = '<span style="color:red"><i class="far fa-clock"></i> '.$datetime->time_before_delivery($row['od_collection']).' to go</span>';
			}else{
				$collect_deliver_by = '<span class="text-purple"><i class="fa fa fa-truck"></i> Deliver TODAY</span>';
				$countdown = '<span style="color:grey"><i class="far fa-clock"></i> 6hrs </span>';
			} 
			
			$new_sales_dropdown .= '<!-- Sale -->
                                            <a href="my_orders.html">
                                                <div class="user-img"> <img src="https://contact25.com/uploads/7_'.$row['od_s_id'].'.jpg" style="width:40px;height:40px;" alt="user" class="img-circle"> <span class="profile-status online pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>'.$row['od_qty'].' x '.$row['od_num'].'</h5> <span class="mail-desc">'.$countdown.'</span> <span class="time">'.$collect_deliver_by.'</span> </div>
                                            </a>';
		}while($row = mysqli_fetch_assoc($query));
		$new_sales_dropdown .= '</div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center link" href="javascript:void(0);"> <strong>All your sales</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>';
	}



$dropdown = '
					
		
								<li> <a href="product-orders.html">My Sales</a> </li>
								<li> <a href="products.html">My Stuff</a> </li>
								<li> <a href="products_friends.html">My Friends\' Stuff</a> </li>
								<li> <a href="my_orders.html">My Orders</a> </li>
							
                      
					';
$messages_dropdown = '
					
		
                                <li><a href="chat_support.html">Support</a></li>
                                <li><a href="chat.html">Chat</a></li>
                          
					
					';
if (($_SERVER['SERVER_NAME'] == 'newapp.contact25.com')||($_SERVER['SERVER_NAME'] == 'contact25.com')){
	$search_icon = '';
}else{
	$search_icon = '<li class="nav-item search_icon_small ">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><em class="fas fa-search"></em>
                           
                            </a>
                           
                        </li>';
}



include_once("../../include/class.chat.php");
$chat = new chat();

$top_menu = '


<ul class="navbar-nav my-lg-0"><!-- ============================================================== -->
                        <!-- Search -->
						
                        <!-- ============================================================== -->
						'.$search_icon.'
                        
						
                        <!-- ============================================================== -->
                        <!-- End Search -->
                        <!-- ============================================================== -->
						
						
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
						
						'.$chat->load_chat_menu_dropdown($_SESSION['c25_id']).'
						
                       
                        <!-- ============================================================== -->
                        <!-- End Comment -->
                        <!-- ============================================================== -->
                       
					   
					   '.$new_sales_dropdown.'
						
						
						
						
                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->
                        
                        
						
						</ul>';

$settings_sidebar = '
			  
     ';

$_SESSION['u_name'] 					= $u_name;
$_SESSION['earnings_last_30_days'] 		= $earnings_last_30_days;
$_SESSION['dropdown'] 					= $dropdown;
$_SESSION['messages_dropdown'] 			= $messages_dropdown;
$_SESSION['top_menu'] 					= $top_menu;
$_SESSION['settings_sidebar'] 			= $settings_sidebar;
$_SESSION['preferences'] 				= $preferences;
$_SESSION['logout'] 					= $logout;


if ($_SESSION['c25_id']>0){
	echo json_encode(array($u_name, $earnings_last_30_days, $dropdown, $messages_dropdown,$top_menu,$settings_sidebar,$preferences, $logout));
}else{
	echo json_encode(array($u_name, $earnings_last_30_days, $dropdown, $messages_dropdown,$top_menu,$settings_sidebar,$preferences, -1));
}





?>